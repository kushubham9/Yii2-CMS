<?php

namespace api\modules\v1\controllers ;

use api\components\Controller ;
use yii\web\BadRequestHttpException ;
use Yii ;

/**
 * Invite Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class InviteController extends Controller
{

    /**
     * @return array
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        $emails = Yii::$app->request->post('email_ids') ;
        $message = Yii::$app->request->post('message') ;
        $users = \common\models\User::find()->where(['email' => $emails])->all() ;
        $isUser = [] ;
        foreach ($users as $user) {
            $isUser[] = $user->email ;
        }
        $sendEmail = array_diff($emails, $isUser) ;
        $messages = [] ;
        foreach ($sendEmail as $email) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages[] = Yii::$app->mailer->compose('Invite Template',                                                          [
                    'message' => $message,
                    'name' => Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name
                ])
                ->setFrom([Yii::$app->user->identity->email => Yii::$app->user->identity->first_name . ' ' . Yii::$app->user->identity->last_name])
                ->enableAsync()
                ->setTo($email)
                ->setTags(['invite'])
                ->setSubject("You have been invited to join indiefolio.com - India’s Largest Creative Community") ;
            } else {
                throw new BadRequestHttpException("{$email} in not a valid email.", 1) ;
            }
        }
        $k = Yii::$app->mailer->sendMultiple($messages) ;
        return ['sent' => $k, 'already_exist' => $isUser] ;
    }
}
