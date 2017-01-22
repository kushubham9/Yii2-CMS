<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 04/01/17
 * Time: 1:28 AM
 */

namespace backend\models;

use common\models\User as BaseUser;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class User extends BaseUser
{
    const ACTIVE_STATUS = 1;

    const SCENARIO_LOGIN = 'login';
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_UPDATE = 'update';


    public $password;
    public $password_repeat;

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_REGISTER] = ['password','password_repeat','username','email','status'];
        $scenarios[self::SCENARIO_UPDATE] = ['password','password_repeat','username','email','status'];
        $scenarios[self::SCENARIO_LOGIN] = ['password','username'];
        return $scenarios;
    }

    public function beforeValidate()
    {
        if ($this->password)
            $this->setPassword($this->password);
        return parent::beforeValidate();
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),
                [
                    ['password'=>'Password'],
                    ['password_repeat'=>'Password Repeat']
                ]);
    }

    public function rules()
    {
        return ArrayHelper::merge([
            [['password','password_repeat'],'required','on'=>self::SCENARIO_REGISTER],
            [['password','password_repeat'],'string','min'=>6],
            ['password_repeat','compare','compareAttribute'=>'password','message'=>"Doesn't match."]
        ],parent::rules());
    }

}