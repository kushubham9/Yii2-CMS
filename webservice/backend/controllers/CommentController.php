<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/01/17
 * Time: 8:28 PM
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use backend\models\CommentSearch;
use yii\filters\AccessControl;

class CommentController extends Controller
{
    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new CommentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
    }
}