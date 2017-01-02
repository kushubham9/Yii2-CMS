<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "post_category".
 *
 * @property integer $post_id
 * @property integer $category_id
 *
 * @property \common\models\Category $category
 * @property \common\models\Post $post
 */
class PostCategory extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'category_id'], 'required'],
            [['post_id', 'category_id'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_category';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'category_id' => 'Category ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(\common\models\Category::className(), ['id' => 'category_id'])->inverseOf('postCategories');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(\common\models\Post::className(), ['id' => 'post_id'])->inverseOf('postCategories');
    }
    }
