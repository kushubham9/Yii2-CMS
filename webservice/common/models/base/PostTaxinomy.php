<?php

namespace common\models\base;

use Yii;

/**
 * This is the base model class for table "post_taxinomy".
 *
 * @property integer $taxinomy_id
 * @property integer $post_id
 *
 * @property \common\models\Post $post
 * @property \common\models\Taxinomy $taxinomy
 */
class PostTaxinomy extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['taxinomy_id', 'post_id'], 'required'],
            [['taxinomy_id', 'post_id'], 'integer']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'post_taxinomy';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'taxinomy_id' => 'Taxinomy ID',
            'post_id' => 'Post ID',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(\common\models\Post::className(), ['id' => 'post_id'])->inverseOf('postTaxinomies');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaxinomy()
    {
        return $this->hasOne(\common\models\Taxinomy::className(), ['id' => 'taxinomy_id'])->inverseOf('postTaxinomies');
    }
    }
