<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 09/01/17
 * Time: 10:20 PM
 */

namespace backend\assets;
use yii\web\AssetBundle;

class wyswyg extends AssetBundle
{
    public $sourcePath = '@webroot/assets/bootstrap-wysihtml5';
    public $css = [
        'bootstrap3-wysihtml5.css'
    ];
    public $js = [
        'bootstrap3-wysihtml5.all.js'
    ];
}