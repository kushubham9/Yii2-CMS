<?php

namespace common\models;

use \common\models\base\Menu as BaseMenu;

/**
 * This is the model class for table "menu".
 */
class Menu extends BaseMenu
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['url'], 'string'],
            [['menu_entity'], 'required'],
            [['menu_entity_id', 'menu_parent', 'display_order'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['menu_entity'], 'string', 'max' => 20]
        ]);
    }
	
}
