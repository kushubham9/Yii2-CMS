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
                <a href="<?= Yii::$app->homeUrl ?>">
                    <span class="logo-mini"><b>A</b>LT</span>
                    <span class="logo-lg"><b>Admin</b>LTE</span>
                </a>

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
                                ['class' => 'btn btn-link logout']
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
                            <?=Html::img('@web/img/user2-160x160.jpg',['class'=>'img-circle']);?>
                        </div>
                        <div class="pull-left info">
                            <p>Alexander Pierce</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>

                    <ul class="sidebar-menu">
                        <li class="header">MAIN NAVIGATION</li>

                        <li class="treeview">
                            <a href="#">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="../../index.html"><i class="fa fa-circle-o"></i> Dashboard v1</a></li>
                                <li><a href="../../index2.html"><i class="fa fa-circle-o"></i> Dashboard v2</a></li>
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
