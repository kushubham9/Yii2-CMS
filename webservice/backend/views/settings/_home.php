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
<div class="row">
    <div class="col-sm-12">
        <h4 class="box-title text-center"> Featured Section Settings </h4>
        <p>&nbsp;</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Enable Featured Widget:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('enable_featured_widget',$settings['enable_featured_widget'],['1'=>'Yes','0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Featured Categories:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('featured_widget_category',unserialize($settings['featured_widget_category']),\common\models\Category::getCategoryDropdown(),['class'=>'form-control','multiple'=>'multiple']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Posts Count:
    </div>
    <div class="col-sm-9">
        <?= Html::input('number','featured_widget_count',$settings['featured_widget_count'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Author Name:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('featured_widget_display_author',$settings['featured_widget_display_author'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Date:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('featured_widget_display_date',$settings['featured_widget_display_date'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Category Badge Count
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('featured_widget_cat_badge_count',$settings['featured_widget_cat_badge_count'],['0'=>'Only 1', '1'=>'All Categories'],['class'=>'form-control']); ?>
    </div>
</div>


<!--Sticky Category 1-->
<p>&nbsp;</p>
<div class="row">
    <div class="col-sm-12">
        <h4 class="box-title text-center"> Sticky Category 1 </h4>
        <p>&nbsp;</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Enable Widget:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_1_enable',$settings['sticky_widget_1_enable'],['1'=>'Yes','0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Default Title:
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','sticky_widget_1_default_title',$settings['sticky_widget_1_default_title'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Categories:
    </div>
    <div class="col-sm-9">
        <?= Html::input('hidden','sticky_widget_1_category',''); ?>
        <?= Html::dropDownList('sticky_widget_1_category',unserialize($settings['sticky_widget_1_category']),\common\models\Category::getCategoryDropdown(),['class'=>'form-control','multiple'=>'multiple']); ?>
        <p class="help-block">Blank will take all recent articles.</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Posts Count:
    </div>
    <div class="col-sm-9">
        <?= Html::input('number','sticky_widget_1_count',$settings['sticky_widget_1_count'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Author Name:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_1_display_author',$settings['sticky_widget_1_display_author'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Date:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_1_display_date',$settings['sticky_widget_1_display_date'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>




<!--Sticky Category 2-->
<p>&nbsp;</p>
<div class="row">
    <div class="col-sm-12">
        <h4 class="box-title text-center"> Sticky Category 2 </h4>
        <p>&nbsp;</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Enable Widget:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_2_enable',$settings['sticky_widget_2_enable'],['1'=>'Yes','0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Default Title:
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','sticky_widget_2_default_title',$settings['sticky_widget_2_default_title'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Categories:
    </div>
    <div class="col-sm-9">
        <?= Html::input('hidden','sticky_widget_2_category',''); ?>
        <?= Html::dropDownList('sticky_widget_2_category',unserialize($settings['sticky_widget_2_category']),\common\models\Category::getCategoryDropdown(),['class'=>'form-control','multiple'=>'multiple']); ?>
        <p class="help-block">Blank will take all recent articles.</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Posts Count:
    </div>
    <div class="col-sm-9">
        <?= Html::input('number','sticky_widget_2_count',$settings['sticky_widget_2_count'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Author Name:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_2_display_author',$settings['sticky_widget_2_display_author'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Date:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_2_display_date',$settings['sticky_widget_2_display_date'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>




<!--Sticky Category 3-->
<p>&nbsp;</p>
<div class="row">
    <div class="col-sm-12">
        <h4 class="box-title text-center"> Sticky Category 3 </h4>
        <p>&nbsp;</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Enable Widget:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_3_enable',$settings['sticky_widget_3_enable'],['1'=>'Yes','0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Default Title:
    </div>
    <div class="col-sm-9">
        <?= Html::input('text','sticky_widget_3_default_title',$settings['sticky_widget_3_default_title'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Categories:
    </div>
    <div class="col-sm-9">
        <?= Html::input('hidden','sticky_widget_3_category',''); ?>
        <?= Html::dropDownList('sticky_widget_3_category',unserialize($settings['sticky_widget_3_category']),\common\models\Category::getCategoryDropdown(),['class'=>'form-control','multiple'=>'multiple']); ?>
        <p class="help-block">Blank will take all recent articles.</p>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Posts Count:
    </div>
    <div class="col-sm-9">
        <?= Html::input('number','sticky_widget_3_count',$settings['sticky_widget_3_count'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Author Name:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_3_display_author',$settings['sticky_widget_3_display_author'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>

<div class="form-group">
    <div class="control-label col-sm-3">
        Display Date:
    </div>
    <div class="col-sm-9">
        <?= Html::dropDownList('sticky_widget_3_display_date',$settings['sticky_widget_3_display_date'],['1'=>'Yes', '0'=>'No'],['class'=>'form-control']); ?>
    </div>
</div>



<div class="form-group">

    <div class="col-sm-9 col-sm-offset-3">
        <?= Html::submitButton('Save',['class'=>'btn btn-primary']); ?>
    </div>
</div>



<?= Html::endForm();?>
