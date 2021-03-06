<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use yii\helpers\Url;
use common\models\Constants;
use yii\bootstrap\ActiveForm;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>

    <meta name="google-site-verification" content="sMQDj9Ewv9y9Th0Fo73GWXN7s7x-RrsIHPEHvv_Cak0" />
    <link rel="shortcut icon" href="http://iamroy.in/client/blog/dist/img/fav.png">
    <title><?= Html::encode($this->title) ?></title>
    <!-- Go to www.addthis.com/dashboard to customize your tools -->
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-58bbb75e60d024fe" async="async"></script>
    <!--    <script type="text/javascript" data-cfasync="false" src="//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js" data-shr-siteid="6bcae693f50b72e6bfabf257e2c9d6f7" async="async"></script>-->
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
<body class="home-purple bk-body">
<?php $this->beginBody() ?>

<div class="site">
    <header class="tz-header">

        <!--Start header top-->
        <div class="tz-header-top">
            <div class="container">

                <!--Header top left-->
                <div class="tz-header-top-left pull-left">
                    <div class="tz-data-time pull-left"><?=\Yii::$app->formatter->asDate('now','long'); ?>  </div>

                </div>
                <!--End header top left-->
                <div class="pull-right">
                    <ul class="top-header-social pull-right">
                        <li>
                            <a target="_blank" href="<?= Yii::$app->params['settings']['social_fb'] ?>"><i class="fa fa-facebook-square"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?= Yii::$app->params['settings']['social_twitter'] ?>"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?= Yii::$app->params['settings']['social_google'] ?>"><i class="fa fa-google"></i></a>
                        </li>
                        <li>
                            <a target="_blank" href="<?= Yii::$app->params['settings']['social_instagram'] ?>"><i class="fa fa-instagram"></i></a>
                        </li>

                    </ul>
<!--                    <div class="tz-hotline pull-right"><i class="fa fa-phone"></i>Call: +(91) 834 903 6366</div>-->
                </div>
            </div>
        </div>
        <!--End header top-->

        <!--Header Content-->
        <div class="tz-header-content">
            <div class="tz-header-logo pull-left">
                <a href="<?= Url::to(['/']); ?>">
                    <img src="<?= Yii::$app->params['settings']['logo_url'] ?>" style="max-height: 115px;" alt="<?= Yii::$app->params['settings']['site_title'] ?>">
                </a>
            </div>

            <!--navigation mobi-->
            <button data-target=".nav-collapse" class="btn-navbar tz_icon_menu" type="button">
                <i class="fa fa-bars"></i>
            </button>
            <!--End navigation mobi-->
        </div>
        <!--Header end content-->

        <!--Header menu-->
        <div class="tz-header-menu">
            <div class="container">

               <?= \frontend\widgets\MainMenu::widget(); ?>

                <!--Start search-->
                <div class="tz-search pull-right">
                    <?= Html::beginForm(Url::to(['/news/search','type'=>'article']),'get',['class'=>'tz-form-search']); ?>
                        <input type="text" name="q" class="input-width" value="" placeholder="Search...">
                        <i class="fa fa-search tz-button-search"></i>
                    <?= Html::endForm();?>
                </div>
                <!--End search-->
            </div>
        </div>

    </header>
    <!--End header-->

    <div class="tz-control">
        <div class="container">
            <!--Breadcrumbs-->
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                'options' => ['class'=>'tz-breadcrumbs pull-left'],
                'itemTemplate' => "<li>{link}<i class=\"fa fa-angle-right\"></i></li>\n"
            ]) ?>

        </div>
    </div>

        <?= $content ?>

    <!--Start Footer-->
    <footer class="tz-footer">
        <div class="container footer-content">
            <div class="row">

                <!--Footer one-->
                <div class="col-md-4 col-sm-6">
                    <div class="widget">
                        <h4 class="widget_title">About Us</h4>
                        <div class="widget_about">
                            <h2><?= Yii::$app->params['settings']['site_title'] ?></h2>
                            <p><?= Yii::$app->params['settings']['site_description'] ?></p>
<!--                            <ul class="widget_about_meta">-->
<!--                                <li>-->
<!--                                    <address>-->
<!--                                        122 Baker St, Surat-->
<!--                                    </address>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    0870 241 3300-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="#">support@tagdabe.com</a>-->
<!--                                </li>-->
<!--                            </ul>-->
                        </div>
                    </div>
                </div>
                <!--End Footer one-->

                <!--Footer Two-->
                <div class="col-md-4 col-sm-6">
                    <div class="widget recent_post">
                        <h4 class="widget_title">Recent Posts</h4>
                        <?= \frontend\widgets\RecentPostWidget::widget(['options' => ['count' => 3]]) ?>
                    </div>
                </div>
                <!--End Footer two-->

                <!--Footer Three-->
<!--                <div class="col-md-3 col-sm-6">-->
<!--                    <div class="widget">-->
<!--                        <h4 class="widget_title">Last tweets</h4>-->
<!---->
<!--                    </div>-->
<!--                </div>-->
                <!--End Footer three-->

                <!--Footer four-->
                <div class="col-md-4 col-sm-6">
                    <div class="widget widget_menu">
                        <h4 class="widget_title">Information</h4>
                        <ul>
                            <li>
                                <a href="#">About Us</a>
                            </li>

                            <li>
                                <a href="#">Contact Us</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--End Footer four-->

            </div>
        </div>

        <div class="tz-copyright">
            <div class="container">
                <p class="pull-left copyright-content">Copyright© Crowdix Technology Pvt. Ltd. All rights reserved.</p>
                <ul class="pull-right footer-social">
                    <li>
                        <a target="_blank"  href="<?= Yii::$app->params['settings']['social_fb'] ?>"><i class="fa fa-facebook-square"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="<?= Yii::$app->params['settings']['social_twitter'] ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="<?= Yii::$app->params['settings']['social_google'] ?>"><i class="fa fa-google"></i></a>
                    </li>
                    <li>
                        <a target="_blank" href="<?= Yii::$app->params['settings']['social_instagram'] ?>"><i class="fa fa-instagram"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </footer>

</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
