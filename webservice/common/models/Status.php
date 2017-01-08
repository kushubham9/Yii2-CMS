<?php

namespace common\models;

use \common\models\base\Status as BaseStatus;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "status".
 */
class Status extends BaseStatus
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['updated_at', 'created_at'], 'required'],
            [['updated_at', 'created_at'], 'safe'],
            [['name'], 'string', 'max' => 25],
            [['name'], 'unique']
        ]);
    }

    public function getUserDropDown()
    {
        return ArrayHelper::map(self::findAll([1,2]), 'id', 'name');
    }
}
