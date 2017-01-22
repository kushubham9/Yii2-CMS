<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 21/01/17
 * Time: 10:14 PM
 */

namespace backend\controllers;
use Yii;
use yii\web\Controller;
use backend\models\Page;
use backend\models\PageSearch;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class PageController extends Controller
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
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Page();
        $model->scenario = Page::SCENARIO_PAGE_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->validate())
        {
            $model->doRegister();
            Yii::$app->session->setFlash('success','New Page Created.');
            $this->redirect(['/page/index']);
        }

        return $this->render('form',[
            'model' => $model
        ]);
    }

    public function actionUpdate($id)
    {
        $model = Page::findOne($id);
        if (!$model)
            throw new NotFoundHttpException('No Page Found. Invalid ID.');

        else{
            if ($model->load(Yii::$app->request->post()) && $model->validate())
            {
                $model->doRegister();
                Yii::$app->session->setFlash('success','Page Details Updated.');
                $this->redirect(['/page/index']);
            }
        }

        return $this->render('form',[
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $model = Page::findOne($id);
        if ($model==null)
        {
            throw new NotFoundHttpException('No Page Found. Invalid ID.');
        }
        $model->delete();
        Yii::$app->session->setFlash('success','Page Deleted.');
        $this->redirect(['/page/index']);
    }
}