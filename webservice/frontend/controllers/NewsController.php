<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 2:11 PM
 */

namespace frontend\controllers;
use yii\web\Controller;
use frontend\models\Posts;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    public function actionIndex(){
        //Get the Query.
        $searchParam = \Yii::$app->request->queryParams;
        $pageTitle = "Latest Posts";
        if (sizeof($searchParam>0)) {
            $pageTitle = "Search Results";
        }
        try{
            $activeData = (new Posts())->search($searchParam);
        }
        catch (\Exception $e){
            $pageTitle = $e->getMessage();
            throw new NotFoundHttpException($e);
        }

        // Get the Post from the activeData
        $post_model = $activeData->getModels();
        $pagination = $activeData->getPagination();
        echo $this->render('index',[
            'model' => $post_model,
            'pagination' => $pagination,
            'pageTitle' => $pageTitle
        ]);
    }
}