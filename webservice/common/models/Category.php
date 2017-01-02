<?php

namespace common\models;

use \common\models\base\Category as BaseCategory;

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
        return array_replace_recursive(parent::rules(),
	    [
            [['name', 'slug', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ]);
    }
	
}
