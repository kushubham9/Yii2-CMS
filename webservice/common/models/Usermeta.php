<?php

namespace common\models;

use \common\models\base\Usermeta as BaseUsermeta;

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
        return array_replace_recursive(parent::rules(),
	    [
            [['about'], 'string'],
            [['updated_at', 'created_at'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['user_id', 'profile_pic'], 'integer'],
            [['first_name', 'last_name', 'nickname'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 1],
            [['website', 'social_fb', 'social_google', 'social_linkedin'], 'string', 'max' => 255]
        ]);
    }
	
}
