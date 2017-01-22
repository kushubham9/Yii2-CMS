<?php

/**
 * Main Config for API
 *
 * PHP version 5
 *
 * @category Config
 * @package  api\config
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com/
 *
 */
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
) ;


$callback = isset($_REQUEST['callback']) ? $_REQUEST['callback'] : false ;
$format = $callback ? 'jsonp' : 'json' ;

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone' => 'Asia/Kolkata',
    'modules' => [
        'v1' => [
            'basePath' => '@app/modules/v1',
            'class' => 'api\modules\v1\Module'
        ]
    ],
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'google' => [
                    'class' => 'yii\authclient\clients\GoogleOAuth',
                    'clientId' => '687511801248-frujctr6dtqv99o8e0b0gc9dbdifgeov.apps.googleusercontent.com',
                    'clientSecret' => 'bq-ZKUhutnpURnvHp9nAGJ4f',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '1615086312089900',
                    'clientSecret' => '630d1e0edfc90410a1df430cbf1db9f3',
                ],
                'linkedin' => [
                    'class' => 'yii\authclient\clients\LinkedIn',
                    'clientId' => '75a9ooayug7sih',
                    'clientSecret' => 'qCzpoMRTW1vPoVoM',
                ],
                'twitter' => [
                    'class' => 'yii\authclient\clients\Twitter',
                    'consumerKey' => 'TPuIatDqxNhws2BLh5176vmsA',
                    'consumerSecret' => 'QHLfcOF9aqB3RKWQYgP1EsF4xSt8GfI5lGvBTYUw4iEhM9wb7l',
                ],
            // etc.
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error',
                        'warning'],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'baseUrl'=>'https://www.indiefolio.com',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        'v1/projects' => 'v1/projects',
                        'v1/jobs' => 'v1/jobs',
                        'v1/spotlight' => 'v1/spotlight',
                        'v1/login' => 'v1/login',
                        'v1/profile' => 'v1/profile',
                        'v1/follow' => 'v1/follow',
                        'v1/applaud' => 'v1/applaud',
                        'v1/bookmark' => 'v1/bookmark',
                        'v1/common' => 'v1/common',
                        'v1/comment' => 'v1/comment',
                        'v1/notification' => 'v1/notification',
                        'v1/message' => 'v1/message',
                        'v1/institute' => 'v1/institute',
                        'v1/upload' => 'v1/upload',
                        'v1/activity' => 'v1/activity',
                        'v1/proxy' => 'v1/proxy',
                        'v1/contact' => 'v1/contact',
                        'v1/search' => 'v1/search',
                        'v1/static' => 'v1/static',
                        'v1/invite' => 'v1/invite',
                    ],
                    'extraPatterns' => [
                        'POST explore' => 'explore',
                        'POST register' => 'register',
                        'POST forgot-password' => 'forgot-password',
                        'POST reset-password' => 'reset-password',
                        'POST job-list' => 'job-list',
                        'POST apply' => 'apply',
                        'POST invite' => 'invite',
                        'POST update-cover' => 'update-cover',
                        'POST update-profile-photo' => 'update-profile-photo',
                        'POST update-profile-details' => 'update-profile-details',
                        'POST publish-status' => 'publish-status',
                        'POST change-status' => 'change-status',
                        'POST remove-bid' => 'remove-bid',
                        'POST delete-bid' => 'delete-bid',
                        'POST delete-job' => 'delete-job',
                        'POST delete-comment' => 'delete-comment',
                        'GET activate' => 'activate',
                        'GET reset-password-token-check' => 'reset-password-token-check',
                        'GET my' => 'my',
                        'GET notifications' => 'notifications',
                        'GET twitter' => 'twitter',
                        'GET spotlight' => 'spotlight',
                        'GET page' => 'view-page',
                        'GET view-less' => 'view-less',
                        'GET view-card' => 'view-card',
                        'GET view-for-edit' => 'view-for-edit',
                        'GET search-term-list' => 'search-term-list',
                        'GET search-tags' => 'search-tags',
                        'GET search-tools' => 'search-tools',
                        'GET search-typefaces' => 'search-typefaces',
                        'GET search-creative-fields' => 'search-creative-fields',
                        'GET search-expertises' => 'search-expertises',
                        'GET search-user-list' => 'search-user-list',
                        'GET search-locations' => 'search-locations',
                        'GET search-job-location' => 'search-job-location',
                        'GET search-job-company' => 'search-job-company',
                        'GET search-job-skill-suggestion' => 'search-job-skill-suggestion',
                        'GET top-creative-fields' => 'top-creative-fields',
                        'GET conversation' => 'conversation',
                        'GET random-project' => 'random-project',
                        'GET slide-projects' => 'slide-projects',
                        'GET drop-down' => 'drop-down',
                        'GET pov-details' => 'pov-details',
                        'GET get-settings' => 'get-settings',
                        'GET test-mail' => 'test-mail',
                        'GET social-link-types' => 'social-link-types',
                        'GET whom-to-follow' => 'whom-to-follow',
                        'GET activity-feed' => 'activity-feed',
                        'GET recommended-projects' => 'recommended-projects',
                        'GET get-current-user-session' => 'get-current-user-session',
                        'GET tour-info' => 'tour-info',
                        'GET search' => 'GET search',
                        'POST request-password-reset' => 'request-password-reset',
                        'POST reset-password' => 'reset-password',
                        'GET country' => 'country',
                        'GET state' => 'state',
                        'GET city' => 'city',
                        'GET software' => 'software',
                        'POST deactivate' => 'deactivate',
                        'POST recruiter' => 'recruiter',
                        'GET coupon' => 'coupon',
                    ],
                    'tokens' => [
                        '{id}' => '<id:[\w-]+>'
                    ]
                ],
            ],
        ],
        'request' => [
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'format' => $format, // json or jsonp
            'on beforeSend' => function ($event) {
                $callback = isset($_REQUEST['callback']) ? $_REQUEST['callback'] : false ;
                $response = $event->sender ;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ] ;
                    if ($callback) {
                        $response->data += ['callback' => $callback] ;
                    }
                    $response->statusCode = 200 ;
                }
            },
        ],
    ],
    'params' => $params,
] ;
