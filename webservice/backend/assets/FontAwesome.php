<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 05/01/17
 * Time: 8:59 PM
 */

namespace backend\assets;
use Yii\web\AssetBundle;

class FontAwesome extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = ['font-awesome/css/font-awesome.css'];
}