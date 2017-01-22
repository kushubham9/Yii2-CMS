<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;
use yii\web\HttpException ;
use Yii ;

/**
 * Notification Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class NotificationController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Notification' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['view']) ;
        unset($actions['index']) ;
        return $actions ;
    }

    /**
     * @return mixed
     */
    public function actionView()
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->notification() ;
        return $dataProvider ;
    }
//    public function actionIndex()
//    {
//        $model = new $this->modelClass ;
//        $dataProvider = $model->notificationAll() ;
//        return $dataProvider ;
//    }


    /**
     * @return mixed
     * @throws HttpException
     */
    public function actionIndex()
    {
        $last_id = Yii::$app->request->get('feed_id') ;
        $client = new \GetStream\Stream\Client(\Yii::$app->params['streamKey'], \Yii::$app->params['streamSecret'], 'v1.0', 'ap-northeast', 50.0) ;
        $user_feed = $client->feed('flat', Yii::$app->user->id) ;

        try {
            $response = $user_feed->getActivities(0, 5, ['id_lt' => $last_id]) ;
        } catch (\Exception $ex) {
            throw new HttpException(500, "Could not connect to activity provider", 1);
        }
        foreach ($response['results'] as $n => $result) {
            switch ($result['verb']) {
                case 'Follow':
                    $user = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]) ;
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes ;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'user':
                            $follow = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]) ;
                            if ($follow) {
                                $response['results'][$n]['info']['following_user_details'] = $follow->feedAttributes ;
                            }
                            break ;
                        default :
                            break ;
                    }
                    break ;
                case 'Unfollow':
                    $user = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]) ;
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes ;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'user':
                            $follow = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]) ;
                            if ($follow) {
                                $response['results'][$n]['info']['following_user_details'] = $follow->feedAttributes ;
                            }
                            break ;
                        default :
                            break ;
                    }
                    break ;
                case 'Bookmarked':
                    $user = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]) ;
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes ;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'Project':
                            $project = \common\models\Project::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]) ;
                            if ($project) {
                                $response['results'][$n]['info']['following_user_details'] = $project->user->feedAttributes ;
                                $response['results'][$n]['info']['entity_details'] = $project->basicAttributes ;
                            }
                            break ;
                        default :
                            break ;
                    }
                    break;
                case 'Applauded':
                    $user = \common\models\User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]) ;
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes ;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'Project':
                            $project = \common\models\Project::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]) ;
                            if ($project) {
                                $response['results'][$n]['info']['following_user_details'] = $project->user->feedAttributes ;
                                $response['results'][$n]['info']['entity_details'] = $project->basicAttributes ;
                            }
                            break ;
                        default :
                            break ;
                    }
                    break;
                default:
                    break ;
            }
        }
        return $response ;
    }
}
