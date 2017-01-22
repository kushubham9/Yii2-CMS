<?php

namespace api\modules\v1\controllers ;

use \api\components\Controller ;
use common\models\BackgroundJob;
use yii\web\HttpException ;
use Yii ;
use \common\models\ContactUs ;
use yii\web\BadRequestHttpException ;
use common\components\AttachedFile ;

/**
 * Contact Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class ContactController extends Controller
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\ContactUs' ;

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new $this->modelClass ;
        $post = Yii::$app->request->post() ;
        if (isset($_FILES['file'])) {
            $base_name = "ProjectImage" ;
            $bucket = ContactUs::S3_CONTACT_FILE ;
            $base_url = ContactUs::CDN_CONTACT_FILE ;


            $file = $_FILES['file'] ;
            $filename = md5($base_name . $file['name'] . microtime() . uniqid() . mt_rand()) ;
            if (AttachedFile::save($file, $bucket, $filename) !== $filename) {
                throw new BadRequestHttpException("File not uploaded.", 1) ;
            }
            $post['ContactUs']['file'] = $filename ;
        }
        if ($model->load(["ContactUs" => $post]) && $model->save()) {
            $model->refresh();
            $params = [
                'email'=>$model->email,
                'subject'=>$model->subject,
                'name'=>$model->name,
                'phone'=>$model->phone,
                'type'=>$model->type,
                'description'=>$model->description,
                'file'=>$model->file,
            ];
            Yii::$app->backgroundJob->add('mail/contact-indiefolio', BackgroundJob::TYPE_CONTACT_INDIEFOLIO, $params);
            return ['msg' => 'Thank you for getting in touch with us. We will get back to you soon'] ;
        } else {
            throw new BadRequestHttpException("Can not save contact details.", 1) ;
        }
    }
}
