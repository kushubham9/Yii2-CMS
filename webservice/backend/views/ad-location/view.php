<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
/* @var $this yii\web\View */
/* @var $model common\models\AdLocation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ad Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header">
                <h3 class="box-title with-border"> View Details </h3>
            </div>
            <div class="box-body">
                <div class="ad-location-view">

                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>

                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'name',
                            'slug',
                            'max_width',
                            'max_height',
                            [
                                'label' => 'Status',
                                'value' => $model->status0->name
                            ],
                            'created_at',
                            'updated_at',
                        ],
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>


