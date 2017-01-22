<?php

namespace api\modules\v1\controllers;

use yii\rest\Controller;
use common\models\LoginForm;


/**
 * User Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class UserController extends Controller
{

    /**
     * @return array|LoginForm
     */
    public function actionLogin()
    {
        $model = new LoginForm() ;

        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {
            return ['auth_key' => Yii::$app->user->identity->getAuthKey()] ;
        } else {
            $model->validate() ;
            return $model ;
        }
    }
}
