<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 12:14 PM
 */
use yii\bootstrap\ActiveForm;
?>

<div id="respond" class="comment-respond">
    <h3 id="reply-title" class="comment-reply-title tz-title">
        <span>Leave a comment</span>
    </h3>
    <?php $form =  ActiveForm::begin(['id'=>'commentform','action'=>\yii\helpers\Url::to(['comment/add']), 'options' => ['class'=>'comment-form']]); ?>
        <div class="row">
            <div class="comment-form-author  col-sm-6">
                <?= $form->field($comment_model, 'author_name')->textInput(['placeholder'=>'Author Name *'])->label(false); ?>
            </div>
            <div class="comment-form-email col-sm-6">
                <?= $form->field($comment_model, 'author_email')->textInput(['placeholder'=>'Email *'])->label(false); ?>
            </div>
            <div class="col-sm-12">
                <?= $form->field($comment_model, 'content')->textarea(['rows'=>8, 'cols'=>40, 'placeholder'=>'Enter your comment *'])->label(false); ?>
            </div>
            <?= $form->field($comment_model, 'post_id')->hiddenInput(['value'=>$postId])->label(false); ?>
            <div class="col-md-12">
                <input name="submit" type="submit" id="submit" class="submit" value="Post Comments">
            </div>
        </div>
    <?php ActiveForm::end();?>
</div>
