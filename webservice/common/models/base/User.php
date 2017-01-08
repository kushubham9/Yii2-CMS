<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property integer $status
 *
 * @property \common\models\Comment[] $comments
 * @property \common\models\Media[] $media
 * @property \common\models\Post[] $posts
 * @property \common\models\Status $status0
 * @property \common\models\Usermeta[] $usermetas
 */
class User extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email','status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['username'], 'string', 'max' => 25],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => 'Email',
            'status' => 'Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\common\models\Comment::className(), ['user_id' => 'id'])->inverseOf('user');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMedia()
    {
        return $this->hasMany(\common\models\Media::className(), ['user_id' => 'id'])->inverseOf('user');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\common\models\Post::className(), ['user_id' => 'id'])->inverseOf('user');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(\common\models\Status::className(), ['id' => 'status'])->inverseOf('users');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
//    public function getUsermetas()
//    {
//        return $this->hasMany(\common\models\Usermeta::className(), ['user_id' => 'id'])->inverseOf('user');
//    }

    public function getUsermeta()
    {
        return $this->hasOne(\common\models\Usermeta::className(), ['user_id' => 'id'])->inverseOf('user');
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
