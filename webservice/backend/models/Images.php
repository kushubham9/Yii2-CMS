<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 21/02/17
 * Time: 8:56 PM
 */

namespace backend\models;


class Images
{
    public static function getImageSrc($ImageManager_id, $width = 400, $height = 400, $thumbnailMode = "outbound"){
        return \Yii::$app->imagemanager->getImagePath($ImageManager_id, $width, $height, $thumbnailMode);
    }
}