<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Please fill out the following fields to login:</h3>
            </div>

            <div class="box-body">
                <?php $form = ActiveForm::begin(
                        [
                                'layout'=>'horizontal',
                                'fieldConfig' => [
                                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{endWrapper}\n{error}",
                                    'horizontalCssClasses' => [
                                        'label' => 'col-sm-3 col-md-2',
                                        'offset' => 'col-sm-offset-3 col-md-offset-2',
                                        'wrapper' => 'col-sm-6',
                                        'error' => 'col-sm-3',
                                        'hint' => '',
                                    ],
                                ]
                        ]);?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-md-offset-2 col-sm-6">
                        <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>