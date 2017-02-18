<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;

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
</head>
<body>
<?php $this->beginBody() ?>

<div class="site">
    <header class="tz-header">
        <!--Start header top-->
        <div class="tz-header-top">
            <div class="container">

                <!--Header Content-->
                <div class="tz-header-content">
                    <div class="tz-header-logo pull-left">
                        <a href="index.html">
                            <img src="http://html.templaza.net/lifemag/images/LifeMag.png" alt="LifeMag">
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
                                    <a href="index.html">
                                        HOME
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="home-video.html">
                                                Home Video
                                            </a>
                                        </li>
                                        <li>
                                            <a href="home-tech.html">
                                                Home Tech
                                            </a>
                                        </li>
                                        <li>
                                            <a href="home-sport.html">
                                                Home Sport
                                            </a>
                                        </li>
                                        <li>
                                            <a href="home-fashion.html">
                                                Home Fashion
                                            </a>
                                        </li>
                                        <li>
                                            <a href="home-rtl.html">
                                                Home RTL
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="active">
                                    <a href="#">
                                        CATEGORIES
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="#">
                                                Categories Columns
                                                <i class="fa fa-plus icon-submenu-down"></i>
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="categories-4column-grid.html">
                                                        4 Columns Grid
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-4column-list.html">
                                                        4 Columns List
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-3column-grid.html">
                                                        3 Columns Grid
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-3column-list.html">
                                                        3 Columns List
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-2column-grid.html">
                                                        2 Columns Grid
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-2column-list.html">
                                                        2 Columns List
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Categories Sidebar
                                                <i class="fa fa-plus icon-submenu-down"></i>
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="blog-2sidebar.html">
                                                        Categories 2 Sidebar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-leftsidebar.html">
                                                        Categories Left Sidebar
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-rightsidebar.html">
                                                        Categories Right Sidebar
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="#">
                                                Categories Style
                                                <i class="fa fa-plus icon-submenu-down"></i>
                                            </a>
                                            <ul class="sub-menu">
                                                <li>
                                                    <a href="categories-blogleftstyle.html">
                                                        Categories Left Style
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="categories-blogrightstyle.html">
                                                        Categories Right Style
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="category-advaned.html">
                                                Category Advaned
                                            </a>
                                        </li>
                                        <li>
                                            <a href="single-blog.html">
                                                Single Post
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Joom Base
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="joom-editprofile.html">Edit Profile</a>
                                        </li>
                                        <li>
                                            <a href="joom-login.html">Joom LogIn</a>
                                        </li>
                                        <li>
                                            <a href="joom-newfeeds.html">Joom Newfeeds </a>
                                        </li>
                                        <li>
                                            <a href="joom-newlink.html">Joom NewLink </a>
                                        </li>
                                        <li>
                                            <a href="joom-register.html">Joom Register</a>
                                        </li>
                                        <li>
                                            <a href="joom-reminduser.html">Joom Remind User</a>
                                        </li>
                                        <li>
                                            <a href="joom-resetaccount.html">Reset Account</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Pages
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <div class="tz-megamenu-wrap width500">
                                        <div class="tz-megamenu menu-two-columns">
                                            <div class="mega-item">
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a href="page-blog.html">
                                                            Page Blog
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="page-fashion.html">
                                                            Page Fashion
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="page-tech.html">
                                                            Page Tech
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="page-travel.html">
                                                            Page Travel
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="page-video.html">
                                                            Page Video
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="mega-item">
                                                <ul class="sub-menu">
                                                    <li>
                                                        <a href="about-us.html">About us</a>
                                                    </li>
                                                    <li>
                                                        <a href="contact.html">Contact</a>
                                                    </li>
                                                    <li>
                                                        <a href="shortcode.html">Shortcode</a>
                                                    </li>
                                                    <li>
                                                        <a href="typography.html">Typography</a>
                                                    </li>
                                                    <li>
                                                        <a href="comingsoon.html">Coming soon</a>
                                                    </li>
                                                    <li>
                                                        <a href="404_page.html">404 page</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                </li>
                                <li>
                                    <a href="#">
                                        Mega Menu
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="megamenu-standard.html">Standard</a>
                                        </li>
                                        <li>
                                            <a href="megamenu-advanced.html">Advanced</a>
                                        </li>
                                        <li>
                                            <a href="megamenu-imagemenu.html">Image Menu</a>
                                        </li>
                                        <li>
                                            <a href="megamenu-content.html">Menu Content</a>
                                        </li>
                                        <li>
                                            <a href="megamenu-flyoutmenu.html">Fly OutMenu</a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#">
                                        Theme color
                                        <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="sub-menu">
                                        <li>
                                            <a href="theme-blue.html">
                                                Theme Dark Blue
                                            </a>
                                        </li>
                                        <li>
                                            <a href="theme-darkblue.html">
                                                Theme Blue
                                            </a>
                                        </li>
                                        <li>
                                            <a href="theme-gold.html">
                                                Theme Gold
                                            </a>
                                        </li>
                                        <li>
                                            <a href="theme-red.html">
                                                Theme Red
                                            </a>
                                        </li>
                                    </ul>
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
                <!--End header menu-->

    </header>
    <!--End header-->


    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'About', 'url' => ['/site/about']],
        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
