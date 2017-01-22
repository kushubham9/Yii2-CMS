<?php

namespace common\models;

use \common\models\base\Category as BaseCategory;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "category".
 */
class Category extends BaseCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return parent::rules();
    }

    public static function getCategoryDropdown()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
