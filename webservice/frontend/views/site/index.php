<?php

/* @var $this yii\web\View */
/* @var $featured_post_model \frontend\models\Posts; */
/* @var $featured_cat_model1 \frontend\models\Posts; */
/* @var $featured_cat_model2 \frontend\models\Posts; */
/* @var $featured_cat_model3 \frontend\models\Posts; */

use yii\helpers\Url;
use frontend\models\Posts;
use frontend\widgets\FeaturedSectionWidget;
use frontend\widgets\FeaturedCategory;

$formatter = \Yii::$app->formatter;

$this->title = \common\models\Constants::SITE_TITLE ." | ".\common\models\Constants::TAGLINE;
?>

<?= FeaturedSectionWidget::widget(['posts_model' => $featured_post_model]); ?>


<!--Content-->
<div class="custom-container">
    <!--Start row-->
    <div class="row border-bottom2">
        <!--Content left-->
        <div class="col-md-9 col-sm-8 style-box1 tzcontent">
            <!--Wrap element-->
            <div class="theiaStickySidebar">

                <?= FeaturedCategory::widget(['type'=>1, 'category' => [1]]); ?>

                <?= FeaturedCategory::widget(['type'=>2, 'count' => 6, 'category' => [1]]); ?>

                <?= FeaturedCategory::widget(['type'=>3, 'count' => 6, 'category' => [6]]); ?>

            </div>
        </div>

        <div class="col-md-3">

        </div>
    </div>
</div>