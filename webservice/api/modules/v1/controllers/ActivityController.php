<?php

namespace api\modules\v1\controllers;

use api\components\Controller;
use yii\web\HttpException;
use Yii;
use \GetStream\Stream\Client;
use \common\models\User;
use \common\models\Project;

/**
 * Activity Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.comm
 */
class ActivityController extends Controller
{

    /**
     * Shows Activities of users
     *
     * @return mixed
     * @throws HttpException
     */
    public function actionActivityFeed()
    {
        $last_id = Yii::$app->request->get('feed_id');
        $client = new Client(\Yii::$app->params['streamKey'], \Yii::$app->params['streamSecret'], 'v1.0', 'ap-northeast', 50.0);
        $user_feed = $client->feed('flat', Yii::$app->user->id);
        try {
            $response = $user_feed->getActivities(0, 20, ['id_lt' => $last_id]);
        } catch (\Exception $ex) {
            throw new HttpException(500, "Could not connect to activity provider", 1);
        }
        foreach ($response['results'] as $n => $result) {
            $date = new \DateTime($result['time'],new \DateTimeZone('UTC'));
            $date->setTimezone(new \DateTimeZone('Asia/Kolkata'));
            $response['results'][$n]['time'] = $date->format('Y-m-d H:m:i');

            switch ($result['verb']) {
                case 'Follow':
                    $user = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]);
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'user':
                            $follow = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]);
                            if (!empty($follow)) {
                                $response['results'][$n]['info']['following_user_details'] = $follow->feedAttributes;
                            }
                            break;
                        default :
                            break;
                    }
                    break;
                case 'Unfollow':
                    $user = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]);
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'user':
                            $follow = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]);
                            if (!empty($follow)) {
                                $response['results'][$n]['info']['following_user_details'] = $follow->feedAttributes;
                            }
                            break;
                        default :
                            break;
                    }
                    break;
                case 'Bookmarked':
                    $user = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]);
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes;
                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'Project':
                            $project = Project::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]);
                            if (!empty($project)) {
                                $response['results'][$n]['info']['following_user_details'] = $project->user->feedAttributes;
                                $response['results'][$n]['info']['entity_details'] = $project->basicAttributes;
                            }
                            break;
                        default :
                            break;
                    }
                    break;
                case 'Applauded':
                    $user = User::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['actor'])]);
                    $response['results'][$n]['info']['user_details'] = $user->feedAttributes;

                    switch (preg_replace('/(^.*):\d.*/', '$1', $result['object'])) {
                        case 'Project':
                            $project = Project::findOne(['id' => preg_replace('/^.*:(\d.*)/', '$1', $result['object'])]);
                            if (!empty($project)) {
                                $response['results'][$n]['info']['following_user_details'] = $project->user->feedAttributes;
                                $response['results'][$n]['info']['entity_details'] = $project->basicAttributes;
                            }
                            break;
                        default :
                            break;
                    }
                    break;
                default:
                    break;
            }
        }
        return $response;
    }
}
