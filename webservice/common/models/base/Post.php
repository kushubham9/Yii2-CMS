<?php

namespace common\models\base;

use common\models\Constants;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the base model class for table "post".
 *
 * @property integer $id
 * @property string $type
 * @property string $title
 * @property string $content
 * @property string $slug
 * @property integer $views
 * @property integer $comment_allowed
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $user_id
 * @property integer $featured_image
 *
 * @property \common\models\Comment[] $comments
 * @property \common\models\Media $featuredImage
 * @property \common\models\Status $status0
 * @property \common\models\User $user
 * @property \common\models\PostCategory[] $postCategories
 * @property \common\models\Category[] $categories
 * @property \common\models\PostTaxinomy[] $postTaximonies
 * @property \common\models\Taxinomy[] $taximonies
 */
class Post extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'title', 'slug', 'created_at', 'updated_at', 'user_id'], 'required'],
            [['content'], 'string'],
            [['views', 'comment_allowed', 'status', 'user_id', 'featured_image'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'title', 'slug'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'title' => 'Title',
            'content' => 'Content',
            'slug' => 'Slug',
            'views' => 'Views',
            'comment_allowed' => 'Comment Allowed',
            'status' => 'Status',
            'user_id' => 'User ID',
            'featured_image' => 'Featured Image',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(\common\models\Comment::className(), ['post_id' => 'id'])->inverseOf('post');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturedImage()
    {
        return $this->hasOne(\common\models\Media::className(), ['id' => 'featured_image'])->inverseOf('posts');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(\common\models\Status::className(), ['id' => 'status'])->inverseOf('posts');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'user_id'])->inverseOf('posts');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(\common\models\PostCategory::className(), ['post_id' => 'id'])->inverseOf('post');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(\common\models\Category::className(), ['id' => 'category_id'])->viaTable('post_category', ['post_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTaxinomies()
    {
        return $this->hasMany(\common\models\PostTaxinomy::className(), ['post_id' => 'id'])->inverseOf('post');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxinomies()
    {
        return $this->hasMany(\common\models\Taxinomy::className(), ['id' => 'taxinomy_id'])->viaTable('post_taxinomy', ['post_id' => 'id']);
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
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ],
        ];
    }
}
