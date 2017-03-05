<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 2:11 PM
 */

namespace frontend\controllers;
use yii\base\InvalidRouteException;
use yii\web\Controller;
use frontend\models\Posts;
use yii\web\NotFoundHttpException;

class NewsController extends Controller
{
    public function actionSearch(){
        //Get the Query Params.
        $searchParam = \Yii::$app->request->queryParams;
        if (!isset($searchParam['q']) || !isset($searchParam['type'])){
            throw new InvalidRouteException('Invalid search parameters.');
        }

        $activeData = (new Posts())->search($searchParam);

        // Get the Post from the activeData
        $post_model = $activeData->getModels();
        $pagination = $activeData->getPagination();

        $post_array = [];
        foreach ($post_model as $post){
            $post_array [] = Posts::getPostInformation($post);
        }

        echo $this->render('search.twig',[
//            'model' => $post_model,
            'posts' => $post_array,
            'pagination' => $pagination,
        ]);
    }

    public function actionIndex()
    {
        return $this->render('index.twig');
    }

    public function actionTest(){
        return $this->render('test');
    }
}
