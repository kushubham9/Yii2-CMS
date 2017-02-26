<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "comment".
 *
 * @property integer $id
 * @property string $author_name
 * @property string $author_email
 * @property string $author_website
 * @property string $author_IP
 * @property string $content
 * @property string $common_agent
 * @property integer $status
 * @property integer $user_id
 * @property integer $post_id
 * @property integer $parent_comment
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Comment $parentComment
 * @property \common\models\Comment[] $comments
 * @property \common\models\Post $post
 * @property \common\models\Status $status0
 * @property \common\models\User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['author_name', 'content', 'post_id', 'author_email'], 'required'],
            [['content'], 'string'],
            [['status', 'user_id', 'post_id', 'parent_comment'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['author_name'], 'string', 'max' => 100],
            [['author_email'], 'string', 'max' => 50],
            [['author_website', 'author_IP', 'common_agent'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'author_name' => 'Author Name',
            'author_email' => 'Author Email',
            'author_website' => 'Author Website',
            'author_IP' => 'Author  Ip',
            'content' => 'Content',
            'common_agent' => 'Common Agent',
            'status' => 'Status',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'parent_comment' => 'Parent Comment',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentComment()
    {
        return $this->hasOne(\common\models\Comment::className(), ['id' => 'parent_comment'])->inverseOf('comments');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\common\models\Comment::className(), ['parent_comment' => 'id'])->inverseOf('parentComment');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(\common\models\Post::className(), ['id' => 'post_id'])->inverseOf('comments');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(\common\models\Status::className(), ['id' => 'status'])->inverseOf('comments');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id'])->inverseOf('comments');
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
