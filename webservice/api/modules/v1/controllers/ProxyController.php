<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;
use yii\web\HttpException ;
use Yii ;

/**
 * Proxy Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class ProxyController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Proxy' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['index'], $actions['create'], $actions['update'], $actions['view'], $actions['delete']) ;
        return $actions ;
    }

    /**
     * @return int
     * @throws HttpException
     */
    public function actionCreate()
    {
        $request = \Yii::$app->request ;
        $proxy_data = $request->post() ;
        $model = new $this->modelClass ;
        if ($model->load(["Proxy" => $proxy_data]) && $model->save()) {
            $messages = [];
            $messages[0] = Yii::$app->mailer->compose('Proxy Request', [
                'name' => $model->name,
                'company_name' => $model->company_name,
                'email' => $model->email,
                'phone' => $model->phone,
                'convenient_time' => $model->convenient_time,
                'details' => $model->details
            ])
                ->setFrom([$model->email => $model->name])
                ->enableAsync()
                ->setTo(Yii::$app->params['proxyMail'])
                ->setSubject('Proxy Request');
            $messages[1] = Yii::$app->mailer->compose('Proxy_User_Reply')
                    ->enableAsync()
                    ->setFrom([\Yii::$app->params['teamEmail'] => \Yii::$app->name . ' Team'])
                    ->setTo($model->email)
                    ->setSubject('IndieFolio Proxy - Thank you for enquiring');
            $k = Yii::$app->mailer->sendMultiple($messages);
            return $k;
        } else {
            throw new HttpException(400, "Error saving details", 1);
        }
    }
}
