<?php

/* @var $this yii\web\View */
/* @var $featured_post_model \frontend\models\Posts; */
/* @var $featured_cat_model1 \frontend\models\Posts; */
/* @var $featured_cat_model2 \frontend\models\Posts; */
/* @var $featured_cat_model3 \frontend\models\Posts; */

use frontend\widgets\FeaturedSectionWidget;
use frontend\widgets\FeaturedCategory;

$this->title = Yii::$app->params['settings']['site_title'] ." | ". Yii::$app->params['settings']['site_tagline'];
?>

<?php
if (Yii::$app->params['settings']['enable_featured_widget']){
    echo FeaturedSectionWidget::widget(['posts_model' => $featured_post_model]);
}
?>


<!--Content-->
<div class="custom-container">
    <!--Start row-->
    <div class="row border-bottom2">
        <!--Content left-->
        <div class="col-md-9 col-sm-8 style-box1 tzcontent">
            <!--Wrap element-->
            <div class="theiaStickySidebar">

                <?php
                if (Yii::$app->params['settings']['sticky_widget_1_enable']) {
                    echo FeaturedCategory::widget(['type'=>1]);
                }
                ?>


                <?php
                if (Yii::$app->params['settings']['sticky_widget_2_enable']) {
                    echo FeaturedCategory::widget(['type'=>2]);
                }
                ?>


                <?php
                if (Yii::$app->params['settings']['sticky_widget_3_enable']) {
                    echo FeaturedCategory::widget(['type'=>3]);
                }
                ?>
            </div>
        </div>

        <div class="col-md-3">

        </div>
    </div>
</div>
<!--Custom Container Ends Here-->