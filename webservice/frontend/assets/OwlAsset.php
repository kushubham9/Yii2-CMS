<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 17/02/17
 * Time: 11:34 PM
 */

namespace frontend\assets;
use yii\web\AssetBundle;

class OwlAsset extends AssetBundle
{
    public $sourcePath = 'owl';
    public $css = [
        '/theme2/owl.css',
//        'owl.carousel.css',
//        'owl.theme.css',
    ];
    public $js = [
        'owl.carousel.js'
    ];
}