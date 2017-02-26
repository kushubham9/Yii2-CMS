<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 3:57 PM
 */

use yii\bootstrap\Html;
?>

<?= Html::beginForm('/settings/update','post',['class'=>'form-horizontal']) ?>
<p>&nbsp;</p>

<div class="form-group">
    <div class="control-label col-sm-3">
        Facebook
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_fb',$settings['social_fb'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Twitter
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_twitter',$settings['social_twitter'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Instagram
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_instagram',$settings['social_instagram'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Youtube
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_youtube',$settings['social_youtube'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Google Plus
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_google',$settings['social_google'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        LinkedIn
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','social_linkedin',$settings['social_linkedin'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">

    <div class="col-sm-9 col-sm-offset-3">
        <?= Html::submitButton('Save',['class'=>'btn btn-primary']); ?>
    </div>
</div>

<?= Html::endForm();?>
