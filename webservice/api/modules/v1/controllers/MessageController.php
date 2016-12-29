<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController;
use common\models\BackgroundJob;
use common\models\Message;
use common\models\MessageUser;
use Yii;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * Message Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class MessageController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\Message' ;

    /**
     * @return array
     */
    public function actions()
    {
        return [];
    }

    /**
     * @param int $archived
     * @return mixed
     */
    public function actionIndex($archived = 0)
    {
        $model = new $this->modelClass;
        $dataProvider = $model->listMsg($archived);
        return $dataProvider;
    }

    /**
     * @return mixed
     * @throws ServerErrorHttpException
     */
    public function actionCreate()
    {
        $model = new $this->modelClass ;
        $model->sender_id = \Yii::$app->user->id;
        $model->content = \Yii::$app->request->post('message');
        $model->conversation_guid = \Yii::$app->request->post('conversation_guid');
        if($model->save()){
            $model->refresh();
            $receivers = \Yii::$app->request->post('receivers');
            foreach ($receivers as $rUserId) {
                $muModel = new MessageUser;
                $muModel->message_id = $model->id;
                $muModel->receiver_id = $rUserId;
                if(!$muModel->save()){
                    throw new ServerErrorHttpException("Error sending message.", 1);
                }
            }
            $muModel = new MessageUser;
            $muModel->message_id = $model->id;
            $muModel->is_read = 1;
            $muModel->receiver_id = \Yii::$app->user->id;
            if(!$muModel->save()){
                throw new ServerErrorHttpException("Error sending message.", 1);
            }


            $mailParams = [
                'from'=>\Yii::$app->user->identity->first_name,
                'message' => $model->content,
                'receivers' => $receivers
            ];

            \Yii::$app->backgroundJob->add('mail/message', BackgroundJob::TYPE_MESSAGE_MAIL, $mailParams);
            return $model;
        } else {
            throw new ServerErrorHttpException("Error sending message.", 1);
        }
    }

    /**
     * @param $guid
     * @return array
     * @throws HttpException
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     */
    public function actionChangeStatus($guid)
    {
        $connection = Yii::$app->db ;
        $transaction = $connection->beginTransaction();
        $request = \Yii::$app->request;
        $user_id = \Yii::$app->user->id;
        $messages = Message::find()->where(['conversation_guid' => $guid]);
        if ($messages->exists()) {
            $is_read = $request->post('is_read', null);
            $is_archived = $request->post('is_archived', null);
            if(!is_null($is_read)) {
                if (in_array($is_read, [0,1])) {
                    $postData = ['is_read' => intval($is_read)];
                } else {
                    throw new HttpException(400, "Invalid Request", 1);
                }
            } elseif (!is_null($is_archived)) {
                if (in_array($is_archived, [0,1])) {
                    $postData = ['is_archived' => intval($is_archived)];
                } else {
                    throw new HttpException(400, "Invalid Request", 1);
                }
            } else {
                throw new HttpException(400, "Invalid Request", 1);
            }

            foreach ($messages->each(100) as $message) {
                $message_user = $message->getMessageUsers()->where(['receiver_id' => $user_id])->one();
                if ($message_user) {
                    $message_user->load(['MessageUser' => $postData]);
                    if (!$message_user->save()) {
                        $transaction->rollBack();
                        throw new HttpException("Error Processing Request", 1);
                    }
                }
            }

            $transaction->commit();
            return $postData;
        } else {
            throw new ServerErrorHttpException("Error fetching conversation.", 1);
        }
    }

    /**
     * @param $id
     * @param int $archived
     * @return array
     * @throws ServerErrorHttpException
     */
    public function actionConversation($id, $archived = 0)
    {
        $model = Message::find()->where(['conversation_guid' => $id])->one();
        if ($model) {
            return $model->getAttributes(null, [], $archived);
        } else {
            throw new ServerErrorHttpException("Error fetching conversation.", 1);
        }
    }

    /**
     * @return array
     * @throws \yii\db\Exception
     */
    public function actionNotifications()
    {
        $userId = \Yii::$app->user->id;
        $model = new $this->modelClass;;
        $inboxProvider = $model->listMsg(0);
        $inboxProvider->query->limit(5);

        $archivedProvider = $model->listMsg(1);
        $archivedProvider->query->limit(5);

        $response = [
            'recievedMessages' => [],
            'sentMessages' => [],
            'unreadCount' => 0
        ];

        foreach ($inboxProvider->models as $message) {
            $response['recievedMessages'][] = $message->indexAttributes;
        }

        foreach ($archivedProvider->models as $message) {
            $response['sentMessages'][] = $message->indexAttributes;
        }

        $sql = "SELECT count(*) as count FROM (SELECT `message`.* FROM `message` LEFT JOIN `message_user` ON `message`.`id` = `message_user`.`message_id` WHERE `message_user`.`receiver_id`= {$userId} AND `message_user`.`is_read` = 0 ORDER BY `message`.`created_at` DESC, `message_user`.`id` DESC) as c GROUP BY c.conversation_guid, c.id ORDER BY c.id DESC";

        $result = Yii::$app->db->createCommand($sql)->execute();
        $response['unreadCount'] = $result;

        return $response;
    }
}
