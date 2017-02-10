<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
?>

<div class="login-box">
    <div class="login-logo">
        <p class="text-strong"> Administration Panel </p>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php $form = ActiveForm::begin(
            [

            ]);?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-block btn-flat', 'name' => 'login-button']) ?>

        <?php ActiveForm::end();?>
    </div>
    <!-- /.login-box-body -->
</div>

