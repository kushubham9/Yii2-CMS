<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use backend\assets\Select2;
use backend\assets\wyswyg;
use common\models\Constants;

Select2::register($this);
wyswyg::register($this);

$this->registerJs("$('select').select2();");
$this->registerJs('$(".wysiwyg").wysihtml5();');

/* @var $post_model backend\models\Post */
/* @var $usermeta_model common\models\Usermeta */
/* @var $status array */


if ($post_model->isNewRecord)
{
    $this->title = "Add New Post";
    $post_model->status = Constants::DEFAULT_POST_STATUS;
    $post_model->category = Constants::DEFAULT_POST_CATEGORY;
    $post_model->comment_allowed = Constants::DEFAULT_COMMENT_ENABLE;
    $post_model->user_id = Yii::$app->user->id;
}

else
{
    $this->title = "Modify Post Details";
    $post_model->category = \yii\helpers\ArrayHelper::getColumn($post_model->categories,'id');
    $post_model->taxinomy = implode(', ', \yii\helpers\ArrayHelper::getColumn($post_model->taxinomies, 'value'));
}

$this->params['breadcrumbs'][] = ['label'=>'Posts','url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Post Details</h3>
            </div>

            <div class="box-body">
                <?php $form = ActiveForm::begin(['layout'=>'default']); ?>
                <?= $form->errorSummary([$post_model]); ?>
                <?= $form->field($post_model,'title')->textInput(['placeholder'=>'Post Title'])->label('Post Title'); ?>
                <?= $form->field($post_model,'content')->textarea(['rows'=>15,'style'=>'width:100%', 'class'=>'wysiwyg'])->label('Post Content'); ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?=$form->field($post_model,'category')
                            ->dropDownList(\common\models\Category::getCategoryDropdown(),['data-placeholder'=>'Select Category','multiple'=>'multiple']);?>
                    </div>

                    <div class="col-sm-6">
                        <?=$form->field($post_model,'taxinomy')->label('Tags');?>
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Publish</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                       <?=$form->field($post_model,'status')
                            ->dropDownList(\common\models\Status::getPostDropDown(),['data-placeholder'=>'Select Status']);?>
                    </div>
                </div>

            </div>
        </div>

        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Discussion</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?=$form->field($post_model,'comment_allowed')->checkbox(['label'=>'Allow comments on the post', 'value'=>'1', 'checked'=>'checked'])?>
                    </div>
                </div>
            </div>
        </div>

        <div class="box box-default">
            <div class="box-header">
                <h3 class="box-title">Post As</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <?=$form->field($post_model,'user_id')
                                ->dropDownList(\common\models\User::getActiveUserDropDown()); ?>
                    </div>
                </div>
            </div>
        </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-md-offset-2 col-sm-6">
                        <?= Html::submitButton($post_model->isNewRecord?'Create':'Update', ['class' => 'btn btn-primary', 'name' => 'create-button']) ?>
                    </div>
                </div>
                <?php ActiveForm::end();?>
            </div>
        </div>

    </div>
</div>

