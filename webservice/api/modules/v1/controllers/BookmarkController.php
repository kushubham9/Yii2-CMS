<?php

namespace api\modules\v1\controllers ;

use Yii;
use api\components\ActiveController ;
use common\models\Bookmark;
use common\models\Project;
use common\models\BackgroundJob;
use \yii\web\HttpException;

/**
 * Bookmark Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class BookmarkController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Bookmark' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['view']) ;
        unset($actions['create']) ;
        return $actions ;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->bookmarkDetails($id) ;
        return $dataProvider ;
    }

    /**
     * @param $project_id
     * @return string
     * @throws HttpException
     * @throws \Exception
     * @throws \yii\db\Exception
     */
    public function actionCreate($project_id)
    {
        $connection = Yii::$app->db ;
        $transaction = $connection->beginTransaction() ;
        try {
            $model = Bookmark::findOne([
                    'user_id' => \Yii::$app->user->id,
                    'entity_id' => $project_id,
                    'entity_type' => 'Project',
                    'status' => 2
                ]);
            if (!is_null($model)) {
                if ($model->delete()) {
                    $project = Project::findOne(['id'=>$project_id]);
                    $project->bookmark_count = $project->bookmark_count - 1;
                    if ($project->save()) {
                        $bookmark = 'Bookmark';

                        $params = [
                            "actor"=>'user:'.\Yii::$app->user->id,
                            "verb"=>$bookmark,
                            "object"=>$model->entity_type.":".$project_id,
                            'foreign_id'=> null,
                            'user_id'=>Yii::$app->user->id
                        ];

                        Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                    } else {
                        throw new HttpException(400, 'Could not remove bookmark.') ;
                    }
                } else {
                    throw new HttpException(400, 'Could not remove bookmark') ;
                }
            } else {
                $model = new $this->modelClass ;
                $model->user_id = \Yii::$app->user->id ;
                $model->entity_id = $project_id ;
                $model->entity_type = 'Project' ;
                $model->status = 2 ;
                if ($model->save()) {
                    $project = Project::findOne(['id' => $project_id]) ;
                    $project->bookmark_count = $project->bookmark_count + 1 ;
                    if ($project->save()) {
                        $bookmark = 'Bookmarked';
                        $params = [
                            "actor"=>"user:".\Yii::$app->user->id,
                            "verb"=>$bookmark,
                            "object"=>$model->entity_type.":".$project_id,
                            'foreign_id'=> $model->id,
                            'user_id'=>Yii::$app->user->id
                        ];

                        Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                    } else {
                        throw new HttpException(400, 'Could not bookmark project.') ;
                    }
                } else {
                    throw new HttpException(400, 'Could not bookmark project') ;
                }
            }
            $transaction->commit() ;
            return $bookmark ;
        } catch (HttpException $ex) {
            $transaction->rollback() ;
            throw new HttpException(400, $ex->getMessage()) ;
        }
    }
}
