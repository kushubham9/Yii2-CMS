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
        //  Yii::$app->cache->flush();
        $settings = $this->db->cache(function($db)
                        {
                            return $db->createCommand("SELECT name,value FROM option")->queryAll();
                        });
        // Now let's load the settings into the global params array

        foreach ($settings as $key => $val) {
            Yii::$app->params['settings'][$val['name']] = $val['value'];
        }
    }

}