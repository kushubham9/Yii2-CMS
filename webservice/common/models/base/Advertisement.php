<?php

namespace common\models\base;

use common\models\Constants;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * This is the base model class for table "advertisement".
 *
 * @property integer $id
 * @property string $title
 * @property string $script
 * @property integer $display_order
 * @property integer $location
 * @property integer $status
 * @property integer $display_mobile
 * @property integer $display_desktop
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Adlocation $location0
 * @property \common\models\Status $status0
 */
class Advertisement extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title','script','location','status'], 'required'],
            [['script'], 'string'],
            [['display_order', 'location', 'status', 'display_mobile', 'display_desktop'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['display_mobile', 'display_desktop'], 'default', 'value'=>1],
            [['status'], 'default', 'value'=>Constants::ACTIVE_AD_STATUS[0]],
            [['display_mobile','display_desktop'], 'boolean'],
            [['title'], 'string', 'max' => 50],
            [['title'], 'unique']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'advertisement';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'script' => 'Script',
            'display_order' => 'Display Order',
            'location' => 'Location',
            'status' => 'Status',
            'display_mobile' => 'Display Mobile',
            'display_desktop' => 'Display Desktop',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocation0()
    {
        return $this->hasOne(\common\models\Adlocation::className(), ['id' => 'location'])->inverseOf('advertisements');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStatus0()
    {
        return $this->hasOne(\common\models\Status::className(), ['id' => 'status'])->inverseOf('advertisements');
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
