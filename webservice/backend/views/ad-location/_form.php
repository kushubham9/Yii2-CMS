<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\models\Status;

/* @var $this yii\web\View */
/* @var $model common\models\AdLocation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="ad-location-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('Unique field used to identify each container') ?>
    <?= $form->field($model, 'max_width')->textInput()->hint('Max width of the container.') ?>
    <?= $form->field($model, 'max_height')->textInput()->hint('Max height of the container') ?>
    <?= $form->field($model, 'status')->dropDownList(Status::getStatusDropDown(\common\models\Constants::AD_LOCATION_STATUS_LIST)) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
