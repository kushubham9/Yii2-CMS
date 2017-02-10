<?php

namespace common\models;

use \common\models\base\AdLocation as BaseAdLocation;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "adlocation".
 */
class AdLocation extends BaseAdLocation
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules();
    }

    public static function getAdLocationDropDown($id = false)
    {
        if ($id)
        {
            return ArrayHelper::map(self::findAll($id),'id','name');
        }
        else
            return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
