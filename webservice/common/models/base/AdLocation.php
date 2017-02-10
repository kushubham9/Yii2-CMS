<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the base model class for table "adlocation".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $max_width
 * @property integer $max_height
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Status $status0
 * @property \common\models\Advertisement[] $advertisements
 */
class AdLocation extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['max_width', 'max_height', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['slug'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['slug'], 'unique']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'adlocation';
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
            'max_width' => 'Max Width',
            'max_height' => 'Max Height',
            'status' => 'Status',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(\common\models\Status::className(), ['id' => 'status'])->inverseOf('adLocations');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAdvertisements()
    {
        return $this->hasMany(\common\models\Advertisement::className(), ['location' => 'id'])->inverseOf('location0');
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
