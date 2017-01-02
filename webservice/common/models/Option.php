<?php

namespace common\models;

use \common\models\base\Option as BaseOption;

/**
 * This is the model class for table "option".
 */
class Option extends BaseOption
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['name'], 'required'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique']
        ]);
    }
	
}
