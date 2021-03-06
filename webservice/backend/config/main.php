<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log','gii','common\config\globalsettings'],
    'modules' => [
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
//        'filemanager' => [
//            'class' => 'dpodium\filemanager\Module',
//            'storage' => ['local'],
//            // This configuration will be used in 'filemanager/files/upload'
//            // To support dynamic multiple upload
//            // Default multiple upload is true, max file to upload is 10
//            // If multiple set to true and maxFileCount is not set, unlimited multiple upload
//            'filesUpload' => [
//                'multiple' => true,
//                'maxFileCount' => 30
//            ],
//            // in mime type format
//            'acceptedFilesType' => [
//                'image/jpeg',
//                'image/png',
//                'image/gif',
//            ],
//            // MB
//            'maxFileSize' => 8,
//            // [width, height], suggested thumbnail size is 120X120
//            'thumbnailSize' => [120,120]
//        ],
        'gii' => [
            'class' => 'yii\gii\Module',
        ],
        'imagemanager' => [
            'class' => 'noam148\imagemanager\Module',
            //set accces rules ()
            'canUploadImage' => true,
            'canRemoveImage' => function(){
                return true;
            },
            //add css files (to use in media manage selector iframe)
            'cssFiles' => [
                'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css',
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'loginUrl' => ['site/login'],
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'imagemanager' => [
            'class' => 'noam148\imagemanager\components\ImageManagerGetPath',
            //set media path (outside the web folder is possible)
            'mediaPath' => 'uploads',
            //path relative web folder to store the cache images
            'cachePath' => 'cache',
            //use filename (seo friendly) for resized images else use a hash
            'useFilename' => true,
            //show full url (for example in case of a API)
            'absoluteUrl' => true,
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'showScriptName' => false,
            'enablePrettyUrl' => true,
            'rules' => array(
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ],
        'frontendCache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache'
        ],

    ],
    'params' => $params,
];
