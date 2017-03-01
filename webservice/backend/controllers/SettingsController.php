<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 3:30 PM
 */

namespace backend\controllers;
use yii\web\Controller;
use common\models\Option;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class SettingsController extends Controller
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
                        'actions' => ['index','update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex(){
        $options = ArrayHelper::map(Option::find()->all(),'name','value');
        return $this->render('index',
            ['settings'=>$options]);
    }

    public function actionUpdate(){
//        die (var_dump(\Yii::$app->request->post()));
        if (\Yii::$app->request->post()){
            foreach (\Yii::$app->request->post() as $key=>$value){
                $option_model = Option::find()->where(['name'=>$key])->one();
                if (!$option_model)
                    continue;

                if (is_array($value)){
                    $value = serialize($value);
                }
                $option_model->value = $value;
                $option_model->save();
            }
        }
        $this->redirect(['/settings']);
    }
}