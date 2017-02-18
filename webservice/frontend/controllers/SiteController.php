<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\models\Posts;
use common\models\Constants;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
//        $post_model = Post::find()->where(['status'=>Constants::DEFAULT_POST_STATUS])->orderBy('created_at DESC')->all();
        $featured_post_model = (new Posts())->featured(null)->getModels();
        $featured_cat_model_1 = (new Posts())->featuredCat(6)->getModels();
        $featured_cat_model_2 = (new Posts())->featuredCat(1)->getModels();
        $featured_cat_model_3 = (new Posts())->featuredCat([1])->getModels();
        return $this->render('index',[
            'featured_post_model'=>$featured_post_model,
            'featured_cat_model1' => $featured_cat_model_1,
            'featured_cat_model2' => $featured_cat_model_2,
            'featured_cat_model3' => $featured_cat_model_3
        ]);
    }
}
