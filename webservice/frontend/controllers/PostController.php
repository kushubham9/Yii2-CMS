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
use common\models\Constants;

class PostController extends Controller
{
    public $defaultAction = 'view';
    public function actionView($slug)
    {
        $post_model = Post::find()->where(['post.slug'=>$slug, 'post.status'=>Constants::DEFAULT_POST_STATUS])->one();
        if (!$post_model)
            throw new NotFoundException("Invalid post specified.");

        $post_model->updateCounters(['views'=>1]);
        echo $this->render('index',[
            'post_model' => $post_model
        ]);
    }
}