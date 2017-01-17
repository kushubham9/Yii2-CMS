<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 14/01/17
 * Time: 1:14 PM
 */

namespace backend\assets;
use yii\web\AssetBundle;

class Select2 extends AssetBundle
{
    public $sourcePath = '@webroot/assets/select2';
    public $css = [
    'select2.css'
    ];
    public $js = [
    'select2.full.min.js'
    ];
    public $depends = ['yii\web\YiiAsset'];
}