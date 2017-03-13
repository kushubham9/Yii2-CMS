<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "status".
 *
 * @property integer $id
 * @property string $name
 * @property string $updated_at
 * @property string $created_at
 *
 * @property \common\models\Comment[] $comments
 * @property \common\models\Post[] $posts
 * @property \common\models\User[] $users
 */
class Status extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['updated_at', 'created_at'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 25],
            [['name'], 'unique']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'status';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\common\models\Comment::className(), ['status' => 'id'])->inverseOf('status0');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\common\models\Post::className(), ['status' => 'id'])->inverseOf('status0');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(\common\models\User::className(), ['status' => 'id'])->inverseOf('status0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdLocations()
    {
        return $this->hasMany(\common\models\AdLocation::className(), ['status' => 'id'])->inverseOf('status0');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertisements()
    {
        return $this->hasMany(\common\models\Advertisement::className(), ['status' => 'id'])->inverseOf('status0');
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
