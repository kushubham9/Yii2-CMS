<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 01/01/17
 * Time: 12:58 PM
 */

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use backend\models\User;
use backend\models\UserSearch;
use common\models\Usermeta;
use common\models\Status;
use yii\web\NotFoundHttpException;
use common\models\LoginForm;

class UserController extends Controller
{
    public $defaultAction = 'index';

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
                        'actions' => ['register', 'index', 'update', 'view'],
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

    /**
     * @return string
     */
    public function actionRegister()
    {
        $usermeta_model = new Usermeta();
        $user_model = new User();
        $user_model->scenario = $user_model::SCENARIO_REGISTER;

        if (Yii::$app->request->post())
            $this->doRegisterUpdate($user_model, $usermeta_model);

        return $this->render('form',[
            'user_model'=>$user_model,
            'usermeta_model'=>$usermeta_model,
            'status'=>(new Status())->getUserDropDown()
        ]);
    }

    /**
     * @param $username string
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($username)
    {
        $user_model = User::findByUsername($username);
        if (!$user_model)
            throw new NotFoundHttpException("Invalid username. No Records Found");

        $user_model->scenario = $user_model::SCENARIO_UPDATE;
        $usermeta_model = Usermeta::findOne(['user_id'=>$user_model->id]);
        if (!$usermeta_model)
            $usermeta_model = new Usermeta();

        if (Yii::$app->request->post())
            $this->doRegisterUpdate($user_model, $usermeta_model);

        return $this->render('form',[
            'user_model'=>$user_model,
            'usermeta_model'=>$usermeta_model,
            'status'=>(new Status())->getUserDropDown()
        ]);

    }

    /**
     * @param $username string
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($username){
        $user_model = User::findOne(['username'=>$username]);
        if (!$user_model)
            throw new NotFoundHttpException("Invalid username. No Records Found");

        return $this->render('view',[
            'user_model'=>$user_model,
        ]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param $userModel \backend\models\User
     * @param $usermetaModel \common\models\Usermeta
     */
    private function doRegisterUpdate($userModel, $usermetaModel)
    {
        if ($userModel->load(Yii::$app->request->post()) && $usermetaModel->load(Yii::$app->request->post())
            && $userModel->validate() && $usermetaModel->validate())
        {
            $userModel->save(false);
            $userModel->refresh();
            if ($userModel->id)
            {
                $usermetaModel->user_id = $userModel->id;
                $usermetaModel->save(false);

                /*Redirect on success*/
                $this->redirect(['user/view','username'=>$userModel->username]);
            }
        }
    }

    public function actionDelete($username)
    {
        $user = User::findByUsername($username);
        if (!$user)
            throw new NotFoundHttpException('No user found.');
        else{
            $user->delete();
            $this->redirect(['user/index']);
        }
    }

}
