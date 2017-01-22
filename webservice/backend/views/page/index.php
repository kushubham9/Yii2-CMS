<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Manage Pages';

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">

            <div class="box-body">
                <div class="page-index">

                        <?php
                        $gridColumn = [
                            ['class' => 'yii\grid\SerialColumn'],
                            ['attribute' => 'id', 'visible' => false],
                            [
                                'attribute'=>'title',
                                'label' => 'Name',
                            ],
                            [
                                'attribute' => 'user_id',
                                'label' => 'Author',
                                'value' => function($model) {
                                    if ($model->user!=null && $model->user->usermeta != null)
                                    {
                                        return $model->user->usermeta->first_name . ' ' . $model->user->usermeta->last_name;
                                    }
                                    return "Not Set";
                                },
                                'filter' => (new \common\models\User())::getActiveUserDropDown(),
                                'filterType'=>GridView::FILTER_SELECT2,
                                'filterInputOptions'=>['placeholder'=>'Any author','multiple'=>'multiple'],
                            ],
                            [
                                'format' => 'html',
                                'header' => 'Comments',
                                'value' => function ($model)
                                {
                                    return $model->comment_allowed ? '<span class="badge">'.count($model->comments).'</span>': "Disabled";
                                },
                            ],
                            [
                                'attribute' => 'status',
                                'label' => 'Status',
                                'format' => 'html',
                                'width' => '100px',
                                'value' => function ($model)
                                {
                                    if ($model->status0 == null)
                                        return 'Not Set';
                                    return $model->status == \common\models\Constants::DEFAULT_POST_STATUS
                                        ?'<span class="label label-success">'.$model->status0->name.'</span>'
                                        :'<span class="label label-danger">'.$model->status0->name.'</span>';
                                },
                            'filter' => Html::activeDropDownList($searchModel,'status',(new \common\models\Status())->getPostDropDown(),['prompt'=>'Select','class'=>'form-control']),
//                                'filter' => (new \common\models\Status())->getPostDropDown(),
//                                'filterWidgetOptions'=>[
//                                    'pluginOptions'=>['allowClear'=>true],
//                                ],
                            ],
                            [
                                'class' => '\kartik\grid\DataColumn',
                                'attribute' => 'updated_at',
                                'filterType' => GridView::FILTER_DATE,
                                'label' => 'Last Updated',
                                'width' => '100px',
                                'format'=>'date'
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
                            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-page']],
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<span class="glyphicon glyphicon-book"></span>  Pages',
                            ],

                        ]); ?>

                    </div>
            </div>
        </div>
    </div>
</div>