<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $user_model backend\models\User */
/* @var $usermeta_model common\models\Usermeta */

$this->title = $user_model->fullName;

$this->params['breadcrumbs'][] = ['label'=>'Users','url'=>['index']];
$this->params['breadcrumbs'][] = $user_model->fullName;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Profile Information</h3>
            </div>
    
            <p>
                <?=Html::a('Update', ['update', 'username' => $user_model->username], ['class' =>'btn btn-primary']) ?>
                <?=Html::a('Delete', ['delete', 'id' => $user_model->username],
                    ['class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post']
                    ]);?>
            </p>

            <?= DetailView::widget(
                [
                    'model'=>$user_model,
                    'attributes' =>
                        [
                            'username',
                            'email:email',
                            [
                                'label' => 'First Name',
                                'value' => $user_model->usermeta->first_name
                            ],
                            [
                                'label' => 'Last Name',
                                'value' => $user_model->usermeta->last_name
                            ],
                            [
                                'label' => 'Nick Name',
                                'value' => $user_model->usermeta->nickname
                            ],
                            [
                                'label' => 'Gender',
                                'value' => $user_model->usermeta->gender == 'F' ? 'Female' : 'Male'
                            ],
                            [
                                'attribute' => 'status',
//                                'value' => $user_model->status0->name,
                                'format' => 'html',
                                 'value' => $user_model::ACTIVE_STATUS == 1
                                                ? "<span class='label label-success'>{$user_model->status0->name}</span>"
                                                : "<span class='label label-danger'>{$user_model->status0->name}</span>"
                            ],
                            'updated_at:datetime',
                            'created_at:datetime'
                        ]
                ]);
            ?>
        </div>
    </div>
</div>

