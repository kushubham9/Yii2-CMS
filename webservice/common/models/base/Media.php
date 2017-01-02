<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "media".
 *
 * @property integer $id
 * @property string $media_type
 * @property string $media_title
 * @property string $media_description
 * @property string $media_url
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 *
 * @property \common\models\User $user
 * @property \common\models\Post[] $posts
 * @property \common\models\Usermeta[] $usermetas
 */
class Media extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['media_type', 'media_url', 'created_at', 'updated_at'], 'required'],
            [['media_description'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_id'], 'integer'],
            [['media_type', 'media_title', 'media_url'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'media_type' => 'Media Type',
            'media_title' => 'Media Title',
            'media_description' => 'Media Description',
            'media_url' => 'Media Url',
            'user_id' => 'User ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id'])->inverseOf('media');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\common\models\Post::className(), ['featured_image' => 'id'])->inverseOf('featuredImage');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsermetas()
    {
        return $this->hasMany(\common\models\Usermeta::className(), ['profile_pic' => 'id'])->inverseOf('profilePic');
    }
    
/**
     * @inheritdoc
     * @return array mixed
     */ 
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new \yii\db\Expression('NOW()'),
            ],
        ];
    }
}
