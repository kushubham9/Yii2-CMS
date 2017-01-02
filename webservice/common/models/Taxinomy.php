<?php

namespace common\models;

use \common\models\base\Taxinomy as BaseTaxinomy;

/**
 * This is the model class for table "taxinomy".
 */
class Taxinomy extends BaseTaxinomy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['type', 'value', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'value'], 'string', 'max' => 255]
        ]);
    }
	
}
