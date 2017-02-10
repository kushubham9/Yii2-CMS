<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\AdLocation */

$this->title = 'Update Ad Location: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Ad Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Update location details</h3>
            </div>
            <div class="box-body">
                <div class="ad-location-update">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


