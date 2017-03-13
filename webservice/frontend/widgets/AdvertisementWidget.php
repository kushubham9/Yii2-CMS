<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 12/03/17
 * Time: 11:38 PM
 */

namespace frontend\widgets;
use common\models\Constants;
use yii\base\Widget;
use common\models\AdLocation;
use common\models\Advertisement;

class AdvertisementWidget extends Widget
{
    public $type;
    private $ads;

    private $config = [];

    public function init()
    {
        parent::init();
        $ad_location_model = AdLocation::find()->where(['slug'=>$this->type, 'status'=>Constants::ACTIVE_AD_LOCATION_STATUS])->one();
        if ($ad_location_model){
            $this->config['max_width'] = $ad_location_model->max_width ? $ad_location_model->max_width : '';
            $this->config['max_height'] = $ad_location_model->max_height ? $ad_location_model->max_height : '';
            $this->ads = Advertisement::find()->where(['location'=>$ad_location_model->id,'status'=>Constants::ACTIVE_AD_STATUS])->orderBy('display_order')->asArray()->all();
        }
    }

    public function run()
    {
        echo $this->render('advertisement.twig',[
            'ads'=>$this->ads,
            'type'=>$this->type,
            'config' => $this->config
        ]);
    }
}