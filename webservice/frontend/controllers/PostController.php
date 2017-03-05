<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 18/02/17
 * Time: 7:47 PM
 */

namespace frontend\controllers;

use Aws\Sns\Exception\NotFoundException;
use frontend\models\Posts;
use yii\web\Controller;
use common\models\Post;
use common\models\Constants;

class PostController extends Controller
{
    public $defaultAction = 'view';
    public function actionView($slug)
    {
        $post_model = Post::find()->with('taxinomies')->with('categories')->where(['post.slug'=>$slug, 'post.status'=>Constants::DEFAULT_POST_STATUS])
//                            joinWith('taxinomies',true)->joinWith('categories',true)
//            ->asArray()
            ->one();
//        die (print_r($post_model));
        if (!$post_model)
            throw new NotFoundException("Invalid post specified.");

        $post_model->updateCounters(['views'=>1]);
        $post_array = Posts::getPostInformation($post_model);
        $post_array['model'] = $post_model;
        echo $this->render('index.twig',[
            'post' => $post_array
        ]);
    }
}