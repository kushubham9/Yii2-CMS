<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 21/01/17
 * Time: 11:30 PM
 */

namespace common\config;

use Yii;
use yii\base\BootstrapInterface;

class globalsettings implements BootstrapInterface
{
    private $db;

    public function __construct() {
        $this->db = Yii::$app->db;
    }

    /**
     * Bootstrap method to be called during application bootstrap stage.
     * Loads all the settings into the Yii::$app->params array
     * @param Application $app the application currently running
     */

    public function bootstrap($app) {

        //Global Settings Stored as params.
//          Yii::$app->cache->flush();

        $settings = Yii::$app->cache->get('GLOBAL_SETTINGS');

        if (!$settings){
            $settings = $this->db->createCommand("SELECT name,value FROM option")->queryAll();
            Yii::$app->cache->set('GLOBAL_SETTINGS',$settings);
        }
//        $settings = $this->db->cache(function($db)
//                        {
//                            return $db->createCommand("SELECT name,value FROM option")->queryAll();
//                        });

        foreach ($settings as $key => $val) {
            Yii::$app->params['settings'][$val['name']] = $val['value'];
        }

        // User Information Stored as Params
        if (Yii::$app->user->id)
        {
            $user_options = $this->db->cache(function($db)
            {
                return $db->createCommand(
                    "SELECT 
                        user.id as user_id, 
                        user.email as user_email, 
                        user.username as user_username, 
                        usermeta.first_name as user_fname, 
                        usermeta.nickname as user_nickname, 
                        usermeta.last_name as user_lname,
                        usermeta.profile_pic as user_image 
                      from user LEFT OUTER JOIN usermeta on 
                        user.id = usermeta.user_id 
                      where user.id = ".Yii::$app->user->id)->queryOne();
            });

            foreach ($user_options as $key=>$val)
            {
                Yii::$app->params['user_details'][$key] = $val;
            }
        }
    }

}