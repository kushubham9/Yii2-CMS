<?php
use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Alert;

$this->title = 'All Posts';
$this->params['breadcrumbs'][] = 'Posts';
?>

<!--@var $dataProvider-->
<!--@var $searchModel-->

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
<!---->
<!--            <div class="box-header with-border">-->
<!--                <h3 class="box-title"></h3>-->
<!--            </div>-->

            <div class="box-body">

                <?=Html::beginForm(['post/bulk'],'post',['class'=>'form-horizontal']);?>
                <div class="form-group">
                    <div class="col-sm-3">
                        <p>
                            <?=Html::dropDownList('action','',[
                                    \backend\controllers\PostController::BULKACTION_PUBLISH=>'Publish',
                                    \backend\controllers\PostController::BULKACTION_HIDE=>'Hide',
                                    \backend\controllers\PostController::BULKACTION_DELETE=>'Delete',],
                                                            ['class'=>'form-control', 'prompt'=>'Bulk Action'])?>
                        </p>
                    </div>
                    <div class="col-sm-9">
                        <p>
                            <?=Html::submitButton('Apply', ['class' => 'btn btn-info',]);?> &nbsp;&nbsp;
                            <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
                        </p>
                    </div>
                </div>
                    <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    'columns' => [
                        ['class' => 'yii\grid\CheckboxColumn'],
                        [
                            'attribute'=>'id',
                            'label' => 'ID',
                            'width'=>'50px',

                        ],
                        'title',
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
                            'filterInputOptions'=>['placeholder'=>'Any Author','multiple'=>'multiple'],
                        ],

                        [
                            'header' => 'Category',
                            'attribute' => 'cat.id',
                            'value' => function($model)
                            {
                                if ($model->categories)
                                {
                                    return implode(', ',\yii\helpers\ArrayHelper::map($model->categories, 'id','name'));
                                }
                                return 'Not Set';
                            },
                            'filterType'=>GridView::FILTER_SELECT2,
                            'filter' => (new \common\models\Category())::getCategoryDropdown(),
                            'filterInputOptions'=>['placeholder'=>'Any Category','multiple'=>'multiple'],

                        ],
                        [
                            'label' => 'Tags',
                            'value' => function($model)
                            {
                                if ($model->taxinomies)
                                {
                                    return implode(', ',\yii\helpers\ArrayHelper::map($model->taxinomies, 'id','value'));
                                }
                                return 'Not Set';
                            },
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
                            'label' => 'Views',
                            'attribute' => 'views',
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
//                            'filter' => (new \common\models\Status())->getPostDropDown(),
//                            'filterWidgetOptions'=>[
//                                'pluginOptions'=>['allowClear'=>true],
//                            ],
//                            'filterInputOptions'=>['placeholder'=>'Any Status'],

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
                            'urlCreator' => function ($action, $model, $key, $index) {
                                if ($action === 'update') {
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                                elseif ($action === 'view'){
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                                elseif ($action === 'delete'){
                                    return Url::toRoute([$action, 'id' => $model->id]);
                                }
                            }
                        ],

                    ],
                    'pjax' => true,
                    'pjaxSettings' => ['options' => ['id' => 'kv-pjax-container-page']],
                    'panel' => [
                        'type' => GridView::TYPE_PRIMARY,
                        'heading' => '<span class="glyphicon glyphicon-book"></span>  '.$this->title,
                    ],

                ]); ?>

                <?= Html::endForm();?>

            </div>
        </div>
    </div>