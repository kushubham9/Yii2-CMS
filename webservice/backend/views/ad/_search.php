<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\AdSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="advertisement-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'script') ?>

    <?= $form->field($model, 'display_order') ?>

    <?= $form->field($model, 'location') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'display_mobile') ?>

    <?php // echo $form->field($model, 'display_desktop') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
