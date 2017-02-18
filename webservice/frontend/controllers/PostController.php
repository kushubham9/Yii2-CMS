<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 18/02/17
 * Time: 7:47 PM
 */

namespace frontend\controllers;

use Aws\Sns\Exception\NotFoundException;
use yii\web\Controller;
use common\models\Post;

class PostController extends Controller
{
    public $defaultAction = 'view';
    public function actionView($slug)
    {
        $post_model = Post::find()->where(['slug'=>$slug])->one();
        if (!$post_model)
            throw new NotFoundException("Invalid post specified.");

        echo $this->render('index',[
            'post_model' => $post_model
        ]);
    }
}