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

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="js/html5shiv.min.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->

</head>
<body>
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
                <div class="tz-header-top-right pull-right">
                    <ul class="top-header-social pull-right">
                        <li>
                            <a href="<?= Constants::SOCIAL_FB; ?>"><i class="fa fa-facebook-square"></i></a>
                        </li>
                        <li>
                            <a href="<?= Constants::SOCIAL_TWITTER; ?>"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="<?= Constants::SOCIAL_GOOGLE; ?>"><i class="fa fa-google"></i></a>
                        </li>
                        <li>
                            <a href="<?= Constants::SOCIAL_INSTAGRAM; ?>"><i class="fa fa-instagram"></i></a>
                        </li>

                    </ul>
                    <div class="tz-hotline pull-right"><i class="fa fa-phone"></i>Call: +(91) 834 903 6366</div>
                </div>
            </div>
        </div>
        <!--End header top-->

        <!--Header Content-->
        <div class="tz-header-content">
            <div class="tz-header-logo pull-left">
                <a href="<?= Url::to(['/']); ?>">
                    <img src="<?= Constants::LOGO_URL ?>" style="max-height: 130px;" alt="<?= Constants::SITE_TITLE ?>">
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

                <!--Main Menu-->
                <nav class="nav-menu pull-left">
                    <ul class="tz-main-menu nav-collapse">
                        <li>
                            <a href="<?= Url::to(['/']); ?>">
                                HOME
                            </a>
<!--                            <ul class="sub-menu">-->
<!--                                <li>-->
<!--                                    <a href="home-video.html">-->
<!--                                        Home Video-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="home-tech.html">-->
<!--                                        Home Tech-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="home-sport.html">-->
<!--                                        Home Sport-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="home-fashion.html">-->
<!--                                        Home Fashion-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                                <li>-->
<!--                                    <a href="home-rtl.html">-->
<!--                                        Home RTL-->
<!--                                    </a>-->
<!--                                </li>-->
<!--                            </ul>-->
                        </li>
                        <li>
                            <a href="#">
                                CATEGORIES
                                <i class="fa fa-angle-down"></i>
                            </a>
                        </li>

                        <li>
                            <a href="#">
                                Pages
                                <i class="fa fa-angle-down"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!--End Main menu-->

                <!--Start search-->
                <div class="tz-search pull-right">
                    <form class="tz-form-search">
                        <input type="text" name="s" class="input-width" value="" placeholder="Search...">
                        <i class="fa fa-search tz-button-search"></i>
                    </form>
                </div>
                <!--End search-->

            </div>
        </div>

    </header>
    <!--End header-->

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
                            <h2><?= Constants::SITE_TITLE; ?></h2>
                            <p><?= Constants::SITE_DESCRIPTION; ?></p>
                            <ul class="widget_about_meta">
                                <li>
                                    <address>
                                        122 Baker St, Surat
                                    </address>
                                </li>
                                <li>
                                    0870 241 3300
                                </li>
                                <li>
                                    <a href="#">support@tagdabe.com</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!--End Footer one-->

                <!--Footer Two-->
                <div class="col-md-4 col-sm-6">
                    <div class="widget recent_post">
                        <h4 class="widget_title">Recent Posts</h4>
                        <?= \frontend\widgets\RecentPostWidget::widget(['count' => 3]) ?>
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
                <p class="pull-left copyright-content">© Copyrights <?=\Yii::$app->formatter->asDate('now','Y'); ?> <?= Html::a('Tagdabe.com','http://www.tagdabe.com'); ?>. All rights reserved.</p>
                <ul class="pull-right footer-social">
                    <li>
                        <a href="<?= Constants::SOCIAL_FB; ?>"><i class="fa fa-facebook-square"></i></a>
                    </li>
                    <li>
                        <a href="<?= Constants::SOCIAL_TWITTER; ?>"><i class="fa fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="<?= Constants::SOCIAL_GOOGLE; ?>"><i class="fa fa-google"></i></a>
                    </li>
                    <li>
                        <a href="<?= Constants::SOCIAL_INSTAGRAM; ?>"><i class="fa fa-instagram"></i></a>
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
