<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>

    <body class="hold-transition skin-blue layout-boxed sidebar-mini">
        <?php $this->beginBody() ?>
        <div class="wrapper">
            <header class="main-header">

                <?php
                    NavBar::begin([
                        'brandLabel' => 'Admin Panel',
                        'brandUrl' => Yii::$app->homeUrl,
                        'innerContainerOptions' => ['class'=>'container-fluid'],
                        'options' => [
                            'class' => 'navbar navbar-static-top',
                        ],
                    ]);
                        $menuItems = [
                        ['label' => 'Home', 'url' => ['/site/index']],
                    ];
                    if (Yii::$app->user->isGuest) {
                        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
                    } else {
                        $menuItems[] = '<li>'
                            . Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Logout (' . Yii::$app->user->identity->username . ')',
                                ['class' => 'btn btn-primary logout']
                            )
                            . Html::endForm()
                            . '</li>';
                    }
                    echo Nav::widget([
                        'options' => ['class' => 'navbar-nav navbar-right nav'],
                        'items' => $menuItems,
                    ]);
                    NavBar::end();
                ?>
            </header>
            <!--    header ends-->

            <!--sidebar-menu-->
            <aside class="main-sidebar">
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <?=Html::img(
                                \Yii::$app->imagemanager->getImagePath(\Yii::$app->params['user_details']['user_image'],60,60)
                                    ,['class'=>'img-circle']);?>
                        </div>
                        <div class="pull-left info">
                            <p><?= Yii::$app->params['user_details']['user_fname'] .' '. Yii::$app->params['user_details']['user_lname']; ?></p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>

                        <li><a href="<?= \yii\helpers\Url::to(['site/index']); ?>"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Posts</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= \yii\helpers\Url::to(['/post/index'])?>"><i class="fa fa-circle-o"></i>All Posts</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['/post/create'])?>"><i class="fa fa-circle-o"></i>Add New</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['/category/index'])?>"><i class="fa fa-circle-o"></i>Categories</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['/taxinomy/index'])?>"><i class="fa fa-circle-o"></i>Tags</a></li>
                            </ul>
                        </li>

                        <li><a href="<?= \yii\helpers\Url::to(['/imagemanager'])?>"><i class="fa fa-book"></i> <span>Media</span></a></li>
                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Pages</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= \yii\helpers\Url::to(['/page/index'])?>"><i class="fa fa-circle-o"></i>All Pages</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['/page/create'])?>"><i class="fa fa-circle-o"></i>Add New</a></li>
                            </ul>
                        </li>
                        <li><a href="../documentation/index.html"><i class="fa fa-book"></i> <span>Comments</span></a></li>

                        <li><a href="../documentation/index.html"><i class="fa fa-book"></i> <span>Advertisement</span></a></li>

                        <li><a href="../documentation/index.html"><i class="fa fa-book"></i> <span>Settings</span></a></li>


                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Users</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?= \yii\helpers\Url::to(['user/index'])?>"><i class="fa fa-circle-o"></i>All Users</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['user/register'])?>"><i class="fa fa-circle-o"></i>Add New</a></li>
                                <li><a href="<?= \yii\helpers\Url::to(['/user/view','username'=>trim(Yii::$app->params['user_details']['user_username'])])?>"><i class="fa fa-circle-o"></i>Your Profile</a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
            </aside>
            <!--sidebar-menu-->

            <div class="content-wrapper">
                <section class="content-header">
                    <h1>
                        <?= $this->title;?>
                    </h1>

                    <?= Breadcrumbs::widget([
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'class'=>'breadcrumb'
                    ]) ?>
                </section>

                <section class="content">
                        <?= Alert::widget() ?>
                        <?= $content ?>
                </section>
            </div>
            <!--    /Content-Wrapper-->

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    <b>Version</b> 2.3.7
                </div>
                <strong><?= Yii::powered() ?></strong>
            </footer>
        </div>
        <!--/Wrapper-->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
