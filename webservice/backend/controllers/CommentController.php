<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/01/17
 * Time: 8:28 PM
 */

namespace backend\controllers;

use common\models\base\Comment;
use common\models\Constants;
use Yii;
use yii\web\Controller;
use backend\models\CommentSearch;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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
                        'actions' => ['index','approve','unapprove','delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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

    public function actionDelete($id){
        $comment_model = \common\models\Comment::findOne($id);
        $comment_model->delete();
        $this->redirect('/comment');
    }

    public function actionApprove($id){
        $comment_model = \common\models\Comment::findOne($id);
        if ($comment_model){
            $comment_model->status = Constants::ACTIVE_COMMENT_STATUS[0];
            $comment_model->save();
        }
        $this->redirect(['/comment']);
    }

    public function actionUnapprove($id){
        $comment_model = \common\models\Comment::findOne($id);
        if ($comment_model){
            $comment_model->status = Constants::DEFAULT_COMMENT_STATUS;
            $comment_model->save();
        }
        $this->redirect(['/comment']);
    }
}