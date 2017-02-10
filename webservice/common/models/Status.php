<?php

namespace common\models;

use \common\models\base\Status as BaseStatus;
use yii\helpers\ArrayHelper;
use common\models\Constants;

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

    public static function getPostDropDown()
    {
        return ArrayHelper::map(self::findAll(Constants::POST_STATUS_LIST), 'id', 'name');
    }

    /**
     * @param $id array
     * @return array
     */
    public static function getStatusDropDown($id = false)
    {
        if ($id)
            return ArrayHelper::map(self::findAll($id), 'id', 'name');
        else
            return ArrayHelper::map(self::find()->all(),'id','name');
    }

}
