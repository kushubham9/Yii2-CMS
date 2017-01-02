<?php

namespace common\models;

use \common\models\base\PostCategory as BasePostCategory;

/**
 * This is the model class for table "post_category".
 */
class PostCategory extends BasePostCategory
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['post_id', 'category_id'], 'required'],
            [['post_id', 'category_id'], 'integer']
        ]);
    }
	
}
