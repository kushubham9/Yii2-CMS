<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertisement-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'script')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'location')->dropDownList(\common\models\AdLocation::getAdLocationDropDown(),['prompt'=>'Select Container']); ?>
    <?= $form->field($model, 'status')->dropDownList(\common\models\Status::getStatusDropDown(\common\models\Constants::AD_STATUS_LIST)) ?>
    <?= $form->field($model, 'display_order')->input('number') ?>
    <?= $form->field($model, 'display_mobile')->checkbox()->label('Mobile Enabled') ?>
    <?= $form->field($model, 'display_desktop')->checkbox()->label('Desktop Enabled') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
