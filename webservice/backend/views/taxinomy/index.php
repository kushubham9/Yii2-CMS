<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\TaxinomySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model common\models\Taxinomy */

use yii\helpers\Html;
use kartik\export\ExportMenu;
use kartik\grid\GridView;
use yii\bootstrap\ActiveForm;

$this->title = 'Manage Tags';

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title"> Manage Tags </h3>
            </div>
            <div class="box-body">
                <div class="col-sm-5">
                    <h4>Add New Tag</h4>
                    <?php $form = ActiveForm::begin(['action'=>['taxinomy/create']]); ?>
                    <?= $form->field($model,'value')->textInput(['maxlength'=>50])->label('Name')->hint('The name is how it appears on your site.'); ?>
                    <?= $form->field($model,'description')->textarea(['rows'=>3])->label('Description')->hint('The description is not prominent by default; however, some themes may show it.'); ?>
                    <div class="form-group">
                        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

                <div class="col-sm-7">
                    <div class="taxinomy-index">

                        <?php
                        $gridColumn = [
                            ['class' => 'yii\grid\CheckboxColumn'],
                            ['attribute' => 'id', 'visible' => false],
                            [
                                'attribute'=>'value',
                                'label' => 'Name',
                            ],
                            'description',
                            [
                                'attribute' => 'slug',
                                'filter' => false,
                            ],
                            [
                                'label' => 'Count',
                                'value' => function ($model)
                                {
                                    return count($model->posts);
                                }
                            ],
                            [
                                'class' => 'yii\grid\ActionColumn',
                            ],
                        ];
                        ?>
                        <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => $gridColumn,
                            'pjax' => true,
                            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-taxinomy']],
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<span class="glyphicon glyphicon-book"></span>  Tags',
                            ],

                        ]); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>