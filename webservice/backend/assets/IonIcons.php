<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 05/01/17
 * Time: 8:47 PM
 */

namespace backend\assets;
use Yii\web\AssetBundle;

class IonIcons extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = ['https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css'];

}