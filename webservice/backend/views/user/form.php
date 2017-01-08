<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $user_model common\models\User */
/* @var $usermeta_model common\models\Usermeta */
/* @var $status array */

if ($user_model->isNewRecord)
    $this->title = "New User Registration";

else
    $this->title = "User Modification";

$this->params['breadcrumbs'][] = ['label'=>'Users','url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Mandatory Information</h3>
            </div>

            <div class="box-body">
                <?php $form = ActiveForm::begin(['layout'=>'horizontal',
                                            'fieldConfig' => [
                                                'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{endWrapper}\n{error}",
                                                'horizontalCssClasses' => [
                                                    'label' => 'col-sm-3 col-md-2',
                                                    'offset' => 'col-sm-offset-3 col-md-offset-2',
                                                    'wrapper' => 'col-sm-6',
                                                    'error' => 'col-sm-3',
                                                    'hint' => '',
                                                ],
                                            ]]);?>
                <?= $form->errorSummary([$user_model,$usermeta_model]); ?>
                <?= $form->field($user_model,'username')->textInput(['autofocus' => true]); ?>
                <?= $form->field($user_model,'email')->input('email'); ?>
                <?php if ($user_model->isNewRecord):?>
                    <?= $form->field($user_model,'password')->passwordInput(); ?>
                    <?= $form->field($user_model,'password_repeat')->passwordInput(); ?>
                <?php endif;?>
                <?php if (!$user_model->isNewRecord):?>
                    <?= $form->field($user_model,'password',['inputOptions'=>['placeholder'=>'Leave blank if N/A']])->label('New Password')->passwordInput(); ?>
                    <?= $form->field($user_model,'password_repeat',['inputOptions'=>['placeholder'=>'Leave blank if N/A']])->label('Password Repeat')->passwordInput(); ?>
                <?php endif;?>

                <?= $form->field($user_model,'status')->dropDownList($status,['prompt'=>'Select Status']); ?>

            </div>
        </div>

        <div class="box box-info form-horizontal">
            <div class="box-header with-border">
                <h3 class="box-title">Other Information</h3>
            </div>
            <div class="box-body">
                <?= $form->field($usermeta_model, 'first_name')->textInput(); ?>
                <?= $form->field($usermeta_model, 'last_name')->textInput(); ?>
                <?= $form->field($usermeta_model, 'nickname')->textInput(); ?>
                <?= $form->field($usermeta_model, 'gender')->dropDownList(['M'=>'Male','F'=>'Female'],['prompt'=>'Select Gender']); ?>
                <?= $form->field($usermeta_model, 'about')->textarea(['rows'=>5]); ?>

            </div>
        </div>

        <div class="box box-info form-horizontal">
            <div class="box-header with-border">
                <h3 class="box-title">Social Profile</h3>
            </div>
            <div class="box-body">
                <?= $form->field($usermeta_model, 'website')->textInput(); ?>
                <?= $form->field($usermeta_model, 'social_fb')->textInput(); ?>
                <?= $form->field($usermeta_model, 'social_google')->textInput(); ?>
                <?= $form->field($usermeta_model, 'social_linkedin')->textInput(); ?>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-md-offset-2 col-sm-6">
                        <?= Html::submitButton($user_model->isNewRecord?'Register':'Update', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>
    </div>
</div>
