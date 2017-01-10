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

    public $css = ['https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css'];
}