<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Advertisement */

$this->title = 'Update Advertisement: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Advertisements', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">New Ad Script</h3>
            </div>
            <div class="box-body">
                <div class="advertisement-create">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>