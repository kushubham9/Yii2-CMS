<?php

namespace common\models\base;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the base model class for table "menu".
 *
 * @property integer $id
 * @property string $title
 * @property string $url
 * @property string $menu_entity
 * @property integer $menu_entity_id
 * @property integer $menu_parent
 * @property integer $display_order
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \common\models\Menu $menuParent
 * @property \common\models\Menu[] $menus
 */
class Menu extends \yii\db\ActiveRecord
{
    use \mootensai\relation\RelationTrait;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['url'], 'string'],
            [['menu_entity'], 'required'],
            [['menu_entity_id', 'menu_parent', 'display_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['menu_entity'], 'string', 'max' => 20]
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'menu';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'menu_entity' => 'Menu Entity',
            'menu_entity_id' => 'Menu Entity ID',
            'menu_parent' => 'Menu Parent',
            'display_order' => 'Display Order',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenuParent()
    {
        return $this->hasOne(\common\models\Menu::className(), ['id' => 'menu_parent'])->inverseOf('menus');
    }
        
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMenus()
    {
        return $this->hasMany(\common\models\Menu::className(), ['menu_parent' => 'id'])->inverseOf('menuParent');
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
