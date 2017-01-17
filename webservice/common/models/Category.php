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
        return
	    [
            [['name', 'slug'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ];
    }

    public static function getCategoryDropdown()
    {
        return ArrayHelper::map(self::find()->all(), 'id', 'name');
    }
}
