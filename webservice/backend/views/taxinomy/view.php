<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model common\models\Taxinomy */

$this->title = $model->value;
$this->params['breadcrumbs'][] = ['label' => 'Taxinomy', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Tag Details </h3>
            </div>

            <div class="box-body">
                <div class="taxinomy-view">

                    <div class="row">
                        <div class="col-sm-3">
                            <p>
                                <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                                <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger',
                                    'data' => [
                                        'confirm' => 'Are you sure you want to delete this item?',
                                        'method' => 'post',
                                    ],
                                ])?>
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <?php
                            $gridColumn = [
                                ['attribute' => 'id'],
                                'type',
                                'slug',
                                'value',
                                'description',
                                'created_at:datetime',
                                'updated_at:datetime',
                            ];
                            echo DetailView::widget([
                                'model' => $model,
                                'attributes' => $gridColumn
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

