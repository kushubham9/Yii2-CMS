<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 04/01/17
 * Time: 1:28 AM
 */

namespace backend\models;

use common\models\Constants;
use common\models\User as BaseUser;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;

class User extends BaseUser
{
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'auth_key', 'password_hash', 'email','status'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'integer'],
            [['status'], 'default', 'value'=>Constants::DEFAULT_USER_STATUS],
            [['username'], 'string', 'max' => 25],
            [['auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['username'], 'unique'],
            [['email'], 'unique'],
            [['password_reset_token'], 'unique'],
            [['fullName'],'safe'],
            [['password','password_repeat'],'required','on'=>self::SCENARIO_REGISTER],
            [['password','password_repeat'],'string','min'=>6],
            ['password_repeat','compare','compareAttribute'=>'password','message'=>"Doesn't match."]
        ];
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

}