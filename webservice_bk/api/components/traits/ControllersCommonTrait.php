<?php


namespace api\components\traits ;

use Yii;
use common\models\User ;
use yii\filters\auth\HttpBearerAuth ;
use yii\filters\ContentNegotiator ;
use yii\web\Response ;


/**
 * Trait that contains needed behaviors for protect controller by OAuth2

 * Class ControllersCommonTrait
 * @package api\components\traits
 * @author Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * Date: 20.04.15
 * Time: 18:54
 * @inheritdoc
 */
trait ControllersCommonTrait
{
    /**
     * @var array
     */
    private static $authConfig = [
        'v1/login/twitter',
        'v1/login/create',
        'v1/login/register',
        'v1/login/activate',
        'v1/login/test-mail',
        'v1/login/forgot-password',
        'v1/login/reset-password-token-check',
        'v1/login/reset-password',
        'v1/common/search-term-list',
        'v1/common/search-tags',
        'v1/common/search-tools',
        'v1/common/search-typefaces',
        'v1/common/search-creative-fields',
        'v1/common/search-expertises',
        'v1/common/search-user-list',
        'v1/common/search-job-location',
        'v1/common/search-job-company',
        'v1/common/search-job-skill-suggestion',
        'v1/common/top-creative-fields',
        'v1/proxy/create',
        'v1/contact/create',
        'v1/profile/view',
        'v1/profile/view-less',
        'v1/profile/view-card',
        'v1/institute/index',
        'v1/institute/view',
        'v1/spotlight/index',
        'v1/projects/view',
        'v1/projects/explore',
        'v1/projects/random-project',
        'v1/projects/slide-projects',
        'v1/common/e-s-test',
        'v1/search/index',
        'v1/static/view-page',
        'v1/login/request-password-reset',
        'v1/common/country',
        'v1/common/state',
        'v1/common/city',
        'v1/common/software',
        'v1/common/recruiter',
        'v1/common/coupon',
        'v1/jobs/view',
    ] ;

    /**
     * @return mixed
     */
    public function behaviors()
    {
        Yii::$app->params['authorization'] = Yii::$app->request->headers->get('authorization', null);

        // echo "<pre>";
        // var_dump(!in_array(Yii::$app->requestedRoute, self::$authConfig) || Yii::$app->params['authorization'] != null);
        // exit;

        $behaviors = parent::behaviors() ;
        // replace to top contentNegotiator filter for displaying errors in correct format
        if (!in_array(Yii::$app->requestedRoute, self::$authConfig) || Yii::$app->params['authorization'] != null) {
            $behaviors['authenticator'] = [
                'class' => HttpBearerAuth::className(),
                'except'=>['options']
            ] ;
        }
        $behaviors['contentNegotiator'] = [
            'class' => ContentNegotiator::className(),
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ] ;
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = true ;
        return $behaviors ;
    }

    /**
     * Access rules for access behavior
     * @return array
     */
    public function accessRules()
    {
        return [] ;
    }
}
