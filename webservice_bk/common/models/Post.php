<?php

namespace common\models;

use \common\models\base\Post as BasePost;

/**
 * This is the model class for table "post".
 */
class Post extends BasePost
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return
	    [
            ['type','default','value'=>Constants::TYPE_POST],
            [['type', 'title', 'slug', 'user_id'], 'required'],
            [['content'], 'string'],
            [['views', 'comment_allowed', 'status', 'user_id', 'featured_image'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ];
    }
	
}
