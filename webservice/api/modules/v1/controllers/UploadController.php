<?php

namespace api\modules\v1\controllers;

use common\models\Upload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use yii\web\BadRequestHttpException;
use api\components\ActiveController;
use common\components\AttachedFile;
use common\models\Project;
use common\models\ProjectBody;

/**
 * Upload Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class UploadController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\components\AttachedFile';

    /**
     * @return array
     */
    public function verbs()
    {
        $verbs = [] ;
        $verbs["create"] = ['POST'];
        return $verbs;
    }

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @param null $type
     * @return string
     * @throws BadRequestHttpException
     */
    public function actionCreate($type = null)
    {
        try {
            if(isset($_FILES['file'])) {
                $file =  $_FILES['file'];
                $tempModel = new Upload();
                $tempModel->imageFile = \yii\web\UploadedFile::getInstanceByName('file');

                if($tempModel->validate()) {
                    $orig_bucket = null;
                    switch ($type) {
                        case 'project_image':
                            $base_name = "ProjectImage";
                            $bucket = ProjectBody::S3_PROJECT_IMAGE;
                            $orig_bucket = ProjectBody::S3_ORIG_PROJECT_IMAGE;
                            $base_url = ProjectBody::CDN_PROJECT_IMAGE . 'original/';
                            break;

                        case 'project_background':
                            $base_name = "ProjectBgImage";
                            $bucket = ProjectBody::S3_PROJECT_BACKGROUND_IMAGE;
                            $orig_bucket = ProjectBody::S3_ORIG_PROJECT_BACKGROUND_IMAGE;
                            $base_url = ProjectBody::CDN_PROJECT_BACKGROUND_IMAGE . 'large/';
                            break;

                        case 'project_cover':
                            $base_name = "Project";
                            $bucket = Project::S3_PROJECT_THUMB;
                            $base_url = Project::CDN_PROJECT_THUMB . 'large/';
                            break;

                        default:
                            throw new BadRequestHttpException("Image type was not present.", 1);
                            break;
                    }
                    $filename = md5($base_name . $file['name'] . microtime() . uniqid() . mt_rand());

                    return $base_url . AttachedFile::saveOther($file, $bucket, $filename, $orig_bucket, $type);
                } else {
                    $msg = implode(',',$tempModel->getErrors('imageFile'));
                    throw new BadRequestHttpException('Only images are allowed.', 400);
                }
            } else {
                throw new BadRequestHttpException("Image was not present.", 400);
            }
        } catch (Exception $ex) {
            echo $ex->getTraceAsString() ;
        }
    }
}
