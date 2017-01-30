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

class CommentController extends Controller
{
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