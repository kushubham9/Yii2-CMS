<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\AdSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Advertisements';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title with-border"> Manage Ads Location </h3>
            </div>

            <div class="box-body">
                <div class="index-ad">
                    <p>
                        <?= Html::a('New Ad', ['create'], ['class' => 'btn btn-success']) ?>
                    </p>

                    <?php Pjax::begin(); ?>    <?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],

                                [
                                    'attribute' => 'id',
                                    'width'=>'45px'
                                ],
                                'title',
                                [
                                    'attribute' => 'location',
                                    'value' => function($mode){
                                            return $model->location0->name;
                                    },
                                    'filter' => Html::activeDropDownList($searchModel,'location',
                                                    \common\models\AdLocation::getAdLocationDropDown(),
                                                    ['prompt'=>'Select','class'=>'form-control']),

                                ],
                                [
                                    'attribute' => 'display_mobile',
                                    'format' => 'raw',
                                    'label' => 'Mobile',
                                    'value' => function($mode){
                                        if ($model->display_mobile == null)
                                            return 'Not Set';
                                        return $model->display_mobile == 1
                                            ?'<span class="label label-success"> Yes </span>'
                                            :'<span class="label label-danger"> No </span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel,'display_mobile',
                                        ['1'=>'Enabled','0'=>'Disabled'],
                                        ['prompt'=>'Select','class'=>'form-control'])
                                ],
                                [
                                    'attribute' => 'display_desktop',
                                    'label' => 'Desktop',
                                    'format' => 'raw',
                                    'value' => function($mode){
                                        if ($model->display_mobile == null)
                                            return 'Not Set';
                                        return $model->display_mobile == 1
                                            ?'<span class="label label-success"> Yes </span>'
                                            :'<span class="label label-danger"> No </span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel,'display_desktop',
                                                    ['1'=>'Enabled','0'=>'Disabled'],
                                                    ['prompt'=>'Select','class'=>'form-control'])
                                ],
                                [
                                    'attribute' => 'status',
                                    'format' => 'raw',
                                    'value' => function ($model)
                                    {
                                        if ($model->status0 == null)
                                            return 'Not Set';
                                        return in_array($model->status, \common\models\Constants::ACTIVE_AD_LOCATION_STATUS)
                                            ?'<span class="label label-success">'.$model->status0->name.'</span>'
                                            :'<span class="label label-danger">'.$model->status0->name.'</span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel,'status',
                                                    \common\models\Status::getStatusDropDown(\common\models\Constants::AD_LOCATION_STATUS_LIST),
                                                    ['prompt'=>'Select','class'=>'form-control']),

                                ],
                                'display_order',
                                ['class' => 'yii\grid\ActionColumn'],
                            ],
                            'pjax' => true,
                            'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-page']],
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<span class="glyphicon glyphicon-book"></span>  '.$this->title,
                            ],
                        ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
