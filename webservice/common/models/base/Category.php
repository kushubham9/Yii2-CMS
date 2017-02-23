<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the base model class for table "category".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property string $created_at
 * @property string $updated_at
 * @property string $description
 * @property integer $parent_category
 * @property string $badge_color
 *
 * @property \common\models\Category $parentCategory
 * @property \common\models\Category[] $categories
 * @property \common\models\PostCategory[] $postCategories
 * @property \common\models\Post[] $posts
 */
class Category extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['created_at', 'updated_at','parent_category'],'safe'],
            [['name'], 'string', 'max' => 50],
            [['slug', 'description', 'badge_color'], 'string', 'max' => 255],
            [['slug'], 'unique']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'description' => 'Description',
            'parent_category' => 'Parent Category',
            'badge_color' => 'Badge Color',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentCategory()
    {
        return $this->hasOne(\common\models\Category::className(), ['id' => 'parent_category']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(\common\models\Category::className(), ['parent_category' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostCategories()
    {
        return $this->hasMany(\common\models\PostCategory::className(), ['category_id' => 'id']);
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\common\models\Post::className(), ['id' => 'post_id'])->viaTable('post_category', ['category_id' => 'id']);
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
                'attribute' => 'name',
            ],
        ];
    }
}


