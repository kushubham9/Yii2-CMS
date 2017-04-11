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

                <?= GridView::widget(
                        [
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'containerOptions'=>['style'=>'overflow: auto', 'class'=>'gridPost'], // only set when $responsive = false
                            'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                            'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                            'pjax'=>true,
                            'panel' => [
                                'type' => GridView::TYPE_PRIMARY,
                                'heading' => '<span class="glyphicon glyphicon-book"></span>  '.$this->title,
                            ],
                            'toolbar' =>[
                                [
                                    'content' =>
                                        Html::dropDownList('action','',[
                                            \backend\controllers\PostController::BULKACTION_PUBLISH=>'Publish',
                                            \backend\controllers\PostController::BULKACTION_HIDE=>'Hide',
                                            \backend\controllers\PostController::BULKACTION_DELETE=>'Delete'],
                                            ['prompt'=>'Bulk Action', 'class'=>'multiAction']).' &nbsp;&nbsp;'.
                                        Html::button('Apply',['class'=>'btn btn-default pull-right applyButton']).
                                        '</div><div class="btn-group">'.
                                        Html::a('New Post',['/post/create'],['class'=>['btn btn-primary pull-right']]),
                                ],
                                '{export}',
                                '{toggleData}'
                            ],

                            'columns' =>
                            [
                                ['class' => 'yii\grid\CheckboxColumn'],
                                [
                                    'attribute'=>'id',
                                    'label' => 'ID',
                                    'width'=>'50px'
                                ],
                                [
                                    'attribute' => 'title',
                                    'format' => 'raw',
                                    'value' => function ($model){
                                        return Html::a($model->title,Url::to(['/post/update','id'=>$model->id]),['title'=>$model->title, 'class'=>'text-strong']);
                                    }
                                ],
                                [
                                    'attribute' => 'user_id',
                                    'label' => 'Author',
                                    'value' => function($model) {
                                        if ($model->user!=null && $model->user->usermeta != null)
                                        {
                                            return $model->user->usermeta->first_name . ' ' . $model->user->usermeta->last_name;
                                        }
                                        else if ($model->user!=null){
                                            return $model->user->username;
                                        }
                                        else {
                                            return "--";
                                        }
                                    },
                                    'filter' => (new \common\models\User())::getActiveUserDropDown(),
                                    'filterType'=>GridView::FILTER_SELECT2,
                                    'filterInputOptions'=>['placeholder'=>'Any Author'],
                                    'enableSorting' => false
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
                                        return '--';
                                    },
                                    'filterType'=>GridView::FILTER_SELECT2,
                                    'filter' => (new \common\models\Category())::getCategoryDropdown(),
                                    'filterInputOptions'=>['placeholder'=>'Any Category'],

                                ],
                                [
                                    'label' => 'Tags',
                                    'value' => function($model)
                                    {
                                        if ($model->taxinomies)
                                        {
                                            return implode(', ',\yii\helpers\ArrayHelper::map($model->taxinomies, 'id','value'));
                                        }
                                        return '——';
                                    },
                                ],
                                [
                                    'header' => 'Comments',
                                    'format' => 'raw',
                                    'value' => function ($model)
                                        {
                                            return $model->comment_allowed ?
                                                '<span class="badge">'.count($model->comments).'</span>':
                                                "<button type='button' class='btn btn-xs btn-danger'>Disabled</button>";
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
                                            return '--';
                                        return $model->status == \common\models\Constants::DEFAULT_POST_STATUS
                                            ?'<span class="label label-success">'.$model->status0->name.'</span>'
                                            :'<span class="label label-danger">'.$model->status0->name.'</span>';
                                    },
                                    'filter' => Html::activeDropDownList($searchModel,'status',(new \common\models\Status())->getPostDropDown(),['prompt'=>'Select','class'=>'form-control']),
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
            //                                return Url::toRoute([$action, 'id' => $model->id]);
                                            return Yii::$app->params['settings']['frontend_address'].'/post/'.$model->slug;
                                        }
                                        elseif ($action === 'delete'){
                                            return Url::toRoute([$action, 'id' => $model->id]);
                                        }
                                    }
                                ],

                            ],

                        ]
                ); ?>

            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs(' 

    $(document).ready(function(){
        function getRows(){
            var strvalue = "";
            $(\'input[name="selection[]"]:checked\').each(function() {
                if(strvalue!="")
                    strvalue = strvalue + ","+this.value;
                else
                    strvalue = this.value;
            });
            return strvalue;
        };
        
        $(\'.applyButton\').click(function(){
            var action = $(\'.multiAction\').val();
            var HotId = getRows();
              $.ajax({
                type: \'POST\',
                url : \'/post/bulk\',
                data : {selection: HotId, action: action},
                
            });
    
        });
        
    });', \yii\web\View::POS_READY);
?>