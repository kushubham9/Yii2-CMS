<?php

use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ad Locations';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title with-border"> Manage Ads Location </h3>
            </div>

            <div class="box-body">
                <div class="ad-location-index">
                    <p>
                        <?= Html::a('Create Ad Location', ['create'], ['class' => 'btn btn-success']) ?>
                    </p>
                    <?php Pjax::begin(); ?>    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            'id',
                            'name',
                            'slug',
                            'max_width',
                            'max_height',
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
                                    }
                            ],
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

