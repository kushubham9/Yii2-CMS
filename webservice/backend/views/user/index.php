<?php
use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = 'Registered Users';
$this->params['breadcrumbs'][] = 'Users';
?>

<!--@var $dataProvider-->
<!--@var $searchModel-->

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">

            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title;?></h3>
            </div>

            <div class="box-body">

                <p>
                    <?= Html::a('Create User', ['register'], ['class' => 'btn btn-success']) ?>
                </p>

                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                       ['class' => 'yii\grid\SerialColumn'],
                        'username',
                        [
                            'attribute' => 'fullName',
                            'label' => 'Name',
                            'value' => function ($model)
                            {
                                return $model->usermeta->first_name. ' '.$model->usermeta->last_name;
                            }
                        ],

                        'email:email',
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_at',
                            'filterType' => GridView::FILTER_DATE,
                            'label' => 'Registration Date',
                            'hAlign'=>'center',
                            'vAlign'=>'middle',
                            'format'=>'date'
                        ],
                        [
                            'attribute' => 'status',
                            'label' => 'Status',
                            'format' => 'html',
                            'value' => function ($model)
                                        {
                                            return $model->status == backend\models\User::ACTIVE_STATUS ?
                                                '<span class="label label-success">'.$model->status0->name.'</span>'
                                                :'<span class="label label-danger">'.$model->status0->name.'</span>';
                                        },
                            'filter' => Html::activeDropDownList($searchModel,'status',(new \common\models\Status())->getUserDropDown(),['prompt'=>'Select','class'=>'form-control'])
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    return Url::toRoute([$action, 'username' => $model->username]);
                                }
                                elseif ($action === 'view'){
                                    return Url::toRoute([$action, 'username' => $model->username]);
                                }
                            }
                        ],
                    ],
                    'bordered'=>true,
                    'striped'=>true,
                    'condensed'=>true,
                    'responsive'=>true,
                    'hover'=>true,
                ]); ?>
            </div>
    </div>
</div>