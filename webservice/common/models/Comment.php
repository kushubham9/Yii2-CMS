<?php

namespace common\models;

use \common\models\base\Comment as BaseComment;

/**
 * This is the model class for table "comment".
 */
class Comment extends BaseComment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['author_name', 'content', 'post_id'], 'required'],
            [['content'], 'string'],
            [['status', 'user_id', 'post_id', 'parent_comment'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['author_name'], 'string', 'max' => 100],
            [['author_email'], 'string', 'max' => 50],
            [['author_website', 'author_IP', 'common_agent'], 'string', 'max' => 255]
        ]);
    }
	
}
