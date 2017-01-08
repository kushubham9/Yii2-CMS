<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/AdminLTE.css',
        'css/skin-blue.css',
        'http://fonts.googleapis.com/css?family=Open+Sans:400,700,800'
    ];
    public $js = [
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'backend\assets\FontAwesome',
        'backend\assets\IonIcons',
    ];
}
