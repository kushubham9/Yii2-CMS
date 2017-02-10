<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model common\models\AdLocation */

$this->title = 'Create Ad Location';
$this->params['breadcrumbs'][] = ['label' => 'Ad Locations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Create new location</h3>
            </div>
            <div class="box-body">
                <div class="ad-location-create">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>


