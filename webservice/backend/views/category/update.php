<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\color\ColorInput;

/* @var $this yii\web\View */
/* @var $model common\models\Category */
/* @var $form yii\widgets\ActiveForm */
$this->title = 'Update';
$this->params['breadcrumbs'][] = ['label' => 'Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Update Categories </h3>
            </div>

            <div class="box-body">
                <?php $form = ActiveForm::begin(); ?>
                <?= $form->errorSummary([$model]); ?>
                <?= $form->field($model, 'name')->textInput(['maxlength' => 50])->hint('The name is how it appears on your site.') ?>
                <?= $form->field($model, 'parent_category')->dropDownList(\common\models\Category::getRootCategoryDropdown(),['prompt'=>'- None -'])->hint('Select parent categoy.') ?>
                <?= $form->field($model, 'slug')->textInput(['maxlength' => 255])->hint('The name is how it appears on your site.') ?>
                <?= $form->field($model, 'description')->textarea(['maxlength' => 255, 'rows'=>3])?>
                <?= $form->field($model, 'badge_color')->widget(ColorInput::classname(), [
                    'options' => ['placeholder' => 'Select color ...'],
                ]); ?>
                <div class="form-group">
                    <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
