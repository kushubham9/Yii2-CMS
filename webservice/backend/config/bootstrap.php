<?php

use yii\base\Event;
use yii\web\User;
use backend\models\User as UserModel;

Event::on(User::className(), User::EVENT_AFTER_LOGIN, function() {
    $user = UserModel::find()->where(['id'=>Yii::$app->user->id])->asArray()->one();
    $session = new \yii\web\Session();
    $session['logged_user'] = $user;
});