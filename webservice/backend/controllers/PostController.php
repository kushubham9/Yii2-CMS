<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 11/01/17
 * Time: 9:44 PM
 */

namespace backend\controllers;

use common\models\Constants;
use Yii;
use yii\web\Controller;
use backend\models\Post;
use backend\models\PostSearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PostController extends Controller
{
    public $defaultAction = 'index';
    const BULKACTION_DELETE = 'Delete';
    const BULKACTION_PUBLISH = 'Publish';
    const BULKACTION_HIDE = 'Hide';

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
                        'actions' => ['create', 'index', 'update','delete', 'bulk'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk' => ['post']
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $post_model = new Post();
        $post_model->scenario = Post::SCENARIO_CREATEPOST;
        if ($post_model->load(Yii::$app->request->post()) && $post_model->validate())
        {
            $post_model->doRegister();
            $this->redirect(['post/index']);
        }

        return $this->render('form',[
            'post_model' => $post_model
        ]);
    }

    public function actionUpdate($id)
    {
        $post_model = Post::findOne($id);
        if (!$post_model)
        {
            throw new NotFoundHttpException('Invalid Post ID Specified.');
        }
        $post_model->scenario = POST::SCENARIO_UPDATEPOST;
        if ($post_model->load(Yii::$app->request->post()) && $post_model->validate())
        {
            $post_model->doRegister();
            $this->redirect(['post/index']);
        }

        return $this->render('form',[
            'post_model' => $post_model
        ]);
    }

    public function actionDelete($id)
    {
        $post_model = Post::findOne($id);
        if ($post_model==null)
        {
            throw new NotFoundHttpException('Invalid Post ID');
        }
        $post_model->delete();
        $this->redirect(['post/index']);
    }

    /**
     * Bulk Action in Posts
     */
    public function actionBulk()
    {
        $action = Yii::$app->request->post('action');
        $posts = Yii::$app->request->post('selection');

        $posts = explode(',',$posts);

        if (empty($posts) || sizeof($posts)==0)
            Yii::$app->session->setFlash('danger','No Posts Selected. Action not performed.');

        if ($action == self::BULKACTION_PUBLISH){
            if ($this->_publishPost($posts))
                Yii::$app->session->setFlash('success','Status Updated.');
        }

        else if ($action == self::BULKACTION_HIDE){
            if ($this->_hidePost($posts))
                Yii::$app->session->setFlash('success','Status Updated.');
        }

        else if ($action == self::BULKACTION_DELETE)
        {
            if ($this->_deletePost($posts))
                Yii::$app->session->setFlash('success','Posts Deleted.');
        }
        $this->redirect(['post/index']);
    }

    /**
     * @param $id
     * @return bool
     * Changes posts status to 'PUBLISHED'
     */
    private function _publishPost($id){
        if (Post::updateAll(['status'=>Constants::STATUS_PUBLISHED],['id'=>$id]))
            return true;

        return false;
    }

    /**
     * @param $id
     * @return bool
     * Changes posts status to 'HIDDEN'
     */
    private function _hidePost($id){
        if (Post::updateAll(['status'=>Constants::STATUS_HIDDEN],['id'=>$id]))
            return true;

        return false;
    }

    /**
     * @param $id
     * @return bool
     * DELETES posts.
     */
    private function _deletePost($id){
        if (Post::deleteAll(['id'=>$id]))
            return true;

        return false;
    }

}