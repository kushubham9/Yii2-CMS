<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the base model class for table "taxinomy".
 *
 * @property integer $id
 * @property string $type
 * @property string $slug
 * @property string $value
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\PostTaxinomy[] $postTaxinomies
 * @property \common\models\Post[] $posts
 */
class Taxinomy extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'value','slug'], 'required'],
            [['slug'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'value', 'description','slug'], 'string', 'max' => 255]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'taxinomy';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'value' => 'Value',
            'description' => 'Description',
            'slug' => 'Slug',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPostTaxinomies()
    {
        return $this->hasMany(\common\models\PostTaxinomy::className(), ['taxinomy_id' => 'id'])->inverseOf('taxinomy');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(\common\models\Post::className(), ['id' => 'post_id'])->viaTable('post_taxinomy', ['taxinomy_id' => 'id']);
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
                'attribute' => 'value',
            ],
        ];
    }
}
