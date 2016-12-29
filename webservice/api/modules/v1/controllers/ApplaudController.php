<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;
use common\models\BackgroundJob;
use common\models\Applaud;
use common\models\Project;
use yii\web\HttpException ;
use Yii ;

/**
 * Applaud Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class ApplaudController extends ActiveController
{

    /**
     * @var string
     * @inheritdoc
     */
    public $modelClass = 'common\models\Applaud' ;

    /**
     * @return array
     * @inheritdoc
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['create']) ;
        unset($actions['view']) ;
        return $actions ;
    }

    /**
     * @param $id
     * @return mixed
     * @inheritdoc
     */
    public function actionView($id)
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->applaudDetails($id) ;
        return $dataProvider ;
    }

    /**
     * @inheritdoc
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
            $model = Applaud::findOne([
                    'user_id' => \Yii::$app->user->id,
                    'entity_id' => $project_id,
                    'entity_type' => 'Project'
                ]);
            if (!is_null($model)) {
                if ($model->delete()) {
                    $project = Project::findOne(['id'=>$project_id]);
                    $project->applaud_count = $project->applaud_count - 1;
                    if ($project->save()) {
                        $applaud = 'Applaud';
                        $params = [
                            "actor"=>"user:".\Yii::$app->user->id,
                            "verb"=>$applaud,
                            "object"=>$model->entity_type.":".$project_id,
                            'foreign_id'=> null,
                            'user_id'=>Yii::$app->user->id
                        ];

                        Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);

                    } else {
                        throw new HttpException(400, 'Could not remove applaud.') ;
                    }
                } else {
                    throw new HttpException(400, 'Could not remove applaud') ;
                }
            } else {
                $model = new $this->modelClass ;
                $model->user_id = \Yii::$app->user->id ;
                $model->entity_id = $project_id ;
                $model->entity_type = 'Project' ;
                if ($model->save()) {
                    $project = Project::findOne(['id' => $project_id]) ;
                    $project->applaud_count = $project->applaud_count + 1 ;
                    if ($project->save()) {
                        $applaud = 'Applauded';

                        $params = [
                            "actor"=>"user:".\Yii::$app->user->id,
                            "verb"=>$applaud,
                            "object"=>$model->entity_type.":".$project_id,
                            'foreign_id'=> $model->id,
                            'user_id'=>Yii::$app->user->id
                        ];
                        Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                        if ($project->applaud_count % 5 == 0) {
                            $mailParams = [
                                'project_guid'=>$project->project_guid,
                                'email'=> $project->user->email
                            ];
                            Yii::$app->backgroundJob->add('mail/applaud', BackgroundJob::TYPE_APPLAUD_MAIL, $mailParams);
                        }
                    } else {
                        throw new HttpException(400, 'Could not applaud project.') ;
                    }
                } else {
                    throw new HttpException(400, 'Could not applaud project') ;
                }
            }
            $transaction->commit() ;
            return $applaud ;
        } catch (HttpException $ex) {
            $transaction->rollback() ;
            throw new HttpException(400, $ex->getMessage()) ;
        }
    }
}
