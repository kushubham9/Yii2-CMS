<?php


namespace api\components ;

use Yii ;
use yii\base\Component ;


/**
 * Class ActivityComponent
 * @package api\components
 * @inheritdoc
 */
class ActivityComponent extends Component
{

    /**
     * Records the activity of the users and sends it to the service getstream.io
     * @param $params
     * @return bool
     */
    public static function recordActivity($params)
    {
        $client = new \GetStream\Stream\Client(\Yii::$app->params['streamKey'], \Yii::$app->params['streamSecret'], 'v1.0', 'ap-northeast', 50.0);

        $user_feed_1 = $client->feed('user', Yii::$app->user->id);
        if ($params['verb']==='Follow') {
            $user_feed_2 = $client->feed('flat', Yii::$app->user->id);
            $user_feed_2->followFeed('user', preg_replace('/^.*:(\d.*)/', '$1', $params['object']));
        }
        if ($params['verb']==='Unfollow') {
            $user_feed_2 = $client->feed('flat', Yii::$app->user->id);
            $user_feed_2->unfollowFeed('user', preg_replace('/^.*:(\d.*)/', '$1', $params['object']));
        }

        $user_feed_1->addActivity($params);

        return true;
    }
}
