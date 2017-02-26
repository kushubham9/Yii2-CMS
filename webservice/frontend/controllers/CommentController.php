<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 2:05 PM
 */

namespace frontend\controllers;
use common\models\Constants;
use yii\web\Controller;
use common\models\Comment;
use yii\web\NotFoundHttpException;

class CommentController extends Controller
{
    public function actionAdd(){
        if (!\Yii::$app->request->isPost){
            throw new NotFoundHttpException();
            return;
        }

        $comment_model = new Comment();
        $comment_model->author_IP = \Yii::$app->request->userIP;
        $comment_model->common_agent = \Yii::$app->request->userAgent;
        $comment_model->status = Constants::DEFAULT_COMMENT_STATUS;
        if ($comment_model->load(\Yii::$app->request->post()) && $comment_model->save()){
            \Yii::$app->session->setFlash('success','Comment Posted. It will be published once approved.');
        }
        else{
            \Yii::$app->session->setFlash('failure','Unable to publish your comment. Try Again!');
        }
        $this->redirect(\Yii::$app->request->referrer);
    }
}