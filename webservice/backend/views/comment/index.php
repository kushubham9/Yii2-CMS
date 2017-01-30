<?php

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\helpers\Html;
use kartik\grid\GridView;

$this->title = 'Manage Comments';

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
                            'label' => 'Author',
                            'attribute' => 'author_name',
                            'value' => function($model)
                                {
                                   $content = '<b>'.$model->author_name.'</b><br/>';
                                   $content += 'Email: '.$model->author_email.'<br/>';
                                   $content += 'Website: '.$model->author_website.'<br/>';
                                   $content += 'IP: '.$model->author_IP.'<br/>';
                                   return $content;
                                }
                        ],
                        [
                            'attribute' => 'content',
                            'label' => 'Comment',
                            'value' => function($model)
                            {
                                return $model->content;
                            }
                        ],
                        [
                            'label' => 'In Response To',
                            'value' => function($model)
                            {
                                return Html::a($model->post->title,['/post/update','id'=>$model->post->id],['class'=>'text-strong']);
                            }
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
                        ],
                        [
                            'class' => '\kartik\grid\DataColumn',
                            'attribute' => 'created_at',
                            'filterType' => GridView::FILTER_DATE,
                            'label' => 'Last Updated',
                            'width' => '100px',
                            'format'=>'date'
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
                            'heading' => '<span class="glyphicon glyphicon-book"></span>  Comments',
                        ],

                    ]); ?>

                </div>
            </div>
        </div>
    </div>
</div>