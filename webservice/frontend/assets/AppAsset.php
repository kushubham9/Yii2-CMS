<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 05/03/17
 * Time: 3:13 PM
 */

namespace frontend\assets;
use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        '/theme2/grid.css',
        '/theme2/framework.css',
        '/theme2/fonts.css',
        '/theme2/normalize.css',
        '/theme2/colorbox.css',
        '/theme2/style.css',
    ];

    public $js = [
//        '/theme2/js/stickyme.js',
//        '/theme2/js/smoothscroll.js',
//        '/theme2/js/prettify.js',
        '/theme2/js/main.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        '\frontend\assets\FontAwesome',
        '\frontend\assets\OwlAsset',
    ];
}