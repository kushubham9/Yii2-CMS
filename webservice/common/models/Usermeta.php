<?php

namespace common\models;

use \common\models\base\Usermeta as BaseUsermeta;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "usermeta".
 */
class Usermeta extends BaseUsermeta
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
//        return parent::rules();
        return ArrayHelper::merge(
        [
            [['about','first_name','last_name','gender','website','social_fb','social_google','social_linkedin','nickname'],'filter','filter'=>'trim'],
        ],parent::rules());
    }
}
