<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Taxinomy */

$this->title = 'Update Tag: ' . ' ' . $model->value;
$this->params['breadcrumbs'][] = ['label' => 'Taxinomy', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->value, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"> Update Tag Details </h3>
            </div>
            <div class="box-body">
                <div class="taxinomy-update">
                    <?php $form = ActiveForm::begin(['action'=>['taxinomy/create']]); ?>
                    <?= $form->errorSummary([$model]); ?>
                    <?= $form->field($model,'value')->textInput(['maxlength'=>50])->label('Name')->hint('The name is how it appears on your site.'); ?>
                    <?= $form->field($model,'slug')->textInput(['maxlength'=>255, 'placeholder'=>'Auto Generated'])->label('Slug')->hint('The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.'); ?>
                    <?= $form->field($model,'description')->textarea(['rows'=>3])->label('Description')->hint('The description is not prominent by default; however, some themes may show it.'); ?>
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
