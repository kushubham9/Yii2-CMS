<?php

namespace common\models;

use \common\models\base\Media as BaseMedia;

/**
 * This is the model class for table "media".
 */
class Media extends BaseMedia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['media_type', 'media_url', 'created_at', 'updated_at'], 'required'],
            [['media_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'integer'],
            [['media_type', 'media_title', 'media_url'], 'string', 'max' => 255]
        ]);
    }
	
}
