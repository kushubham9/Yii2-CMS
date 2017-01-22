<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Manage Categories </h3>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6 col-md-5">
                        <h4>Add New Category</h4>
                        <?php $form = ActiveForm::begin(['action'=>['category/create']]); ?>
                            <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint('The name is how it appears on your site.') ?>
                            <?= $form->field($model, 'description')->textarea(['maxlength' => true, 'rows'=>3])->hint('The description is not prominent by default.') ?>
                            <div class="form-group">
                                <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                            </div>
                        <?php ActiveForm::end(); ?>
                    </div>

                    <div class="col-sm-6 col-md-7">
                        <div class="category-index">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    'name',
                                    ['attribute'=>'slug', 'filter' => false],
                                    'description',
                                    [
                                        'label' => 'Count',
                                        'value' => function ($model){
                                                return count($model->posts);
                                            }
                                    ],
                                    ['class' => 'yii\grid\ActionColumn'],
                                ],
                                'pjax' => true,
                                'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-taxinomy']],
                                'panel' => [
                                    'type' => GridView::TYPE_PRIMARY,
                                    'heading' => '<span class="glyphicon glyphicon-book"></span>  Category List',
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


