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
        Enable Featured Widget
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList() ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Site Tagline
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','site_tagline',$settings['site_tagline'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Site Description
    </div>
    <div class="col-sm-9">
        <?= Html::textarea('site_description',$settings['site_description'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Frontend Address
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','frontend_address',$settings['frontend_address'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Backend Address
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','backend_address',$settings['backend_address'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Image Server
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','image_base_address',$settings['image_base_address'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Logo Url
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','logo_url',$settings['logo_url'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">

    <div class="col-sm-9 col-sm-offset-3">
        <?= Html::submitButton('Save',['class'=>'btn btn-primary']); ?>
    </div>
</div>

<?= Html::endForm();?>
