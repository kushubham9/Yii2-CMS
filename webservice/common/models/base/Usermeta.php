<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "usermeta".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 * @property string $gender
 * @property string $about
 * @property string $website
 * @property string $social_fb
 * @property string $social_google
 * @property string $social_linkedin
 * @property string $updated_at
 * @property string $created_at
 * @property integer $user_id
 * @property integer $profile_pic
 *
 * @property \common\models\Media $profilePic
 * @property \common\models\User $user
 */
class Usermeta extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['about'], 'string'],
            [['updated_at', 'created_at'], 'safe'],
            [['user_id', 'profile_pic'], 'integer'],
            [['first_name', 'last_name', 'nickname'], 'string', 'max' => 50],
            [['gender'], 'string', 'max' => 1],
            [['website', 'social_fb', 'social_google', 'social_linkedin'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usermeta';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'nickname' => 'Nickname',
            'gender' => 'Gender',
            'about' => 'About',
            'website' => 'Website URL',
            'social_fb' => 'Facebook',
            'social_google' => 'Google +',
            'social_linkedin' => 'Linkedin',
            'user_id' => 'User ID',
            'profile_pic' => 'Profile Pic',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfilePic()
    {
        return $this->hasOne(\common\models\Media::className(), ['id' => 'profile_pic'])->inverseOf('usermetas');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id'])->inverseOf('usermetas');
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
