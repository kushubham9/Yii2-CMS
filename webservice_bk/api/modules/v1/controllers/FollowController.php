<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;
use common\models\BackgroundJob;
use \common\models\Follow;
use \common\models\User;

/**
 * Follow Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class FollowController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Follow' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['create']) ;
        return $actions ;
    }

    /**
     * @return bool|int
     * @throws \Exception
     */
    public function actionCreate()
    {
        $loggedInUser = \Yii::$app->user->id ;
        $entityType = \Yii::$app->request->post('entity_type') ;
        $entityId = \Yii::$app->request->post('entity_id') ;
        $loadData['Follow'] = [
            'user_id' => $loggedInUser,
            'entity_type' => $entityType,
            'entity_id' => $entityId
        ] ;
        $model = Follow::findOne($loadData['Follow']) ;
        if (empty($model)) {
            $model = new $this->modelClass ;
            if ($model->load($loadData) && $model->save()) {
                $params = [
                    "actor" => "user:" . \Yii::$app->user->id,
                    "verb" => 'Follow',
                    "object" => $model->entity_type . ":" . $model->entity_id,
                    'foreign_id' => $model->id,
                    'user_id'=>\Yii::$app->user->id
                ];

                \Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                return true ;
            }
        } else {
            if ($model->delete()) {
                $params = [
                    "actor" => "user:" . \Yii::$app->user->id,
                    "verb" => 'Unfollow',
                    "object" => $model->entity_type . ":" . $model->entity_id,
                    'foreign_id' => null,
                    'user_id'=>\Yii::$app->user->id
                ];

                \Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                return -1 ;
            }
        }
        return false ;
    }

    /**
     * @param int $count
     * @return \yii\data\ActiveDataProvider
     */
    public function actionWhomToFollow($count = 6)
    {
        $dataProvider = User::whomToFollow($count) ;
        return $dataProvider ;
    }
}
