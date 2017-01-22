<?php

namespace api\modules\v1\controllers ;

use common\models\Comment;
use common\models\BackgroundJob;
use api\components\ActiveController ;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

/**
 * Comment Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class CommentController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Comment' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['view']) ;
        unset($actions['create']) ;
        return $actions ;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->comments($id) ;
        return $dataProvider ;
    }

    /**
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionCreate()
    {
        $model = new $this->modelClass ;

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                $params = [
                    "actor"=>"user:".\Yii::$app->user->id,
                    "verb"=>'Commented',
                    "object"=>$model->entity_type.":".$model->entity_id,
                    'foreign_id'=> $model->id,
                    'user_id'=>\Yii::$app->user->id
                ];

                \Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);

                $mailParams = [
                    'project_id'=>$model->entity_id,
                    'from'=>\Yii::$app->user->identity->first_name.' '.\Yii::$app->user->identity->last_name,
                    'comment' => $model->comment
                ];

                \Yii::$app->backgroundJob->add('mail/comment', BackgroundJob::TYPE_COMMENT_MAIL, $mailParams);
                return $model->indexAttributes;
            } else {
                throw new BadRequestHttpException('Bad request');
            }
        } else {
            throw new BadRequestHttpException('Bad request');
        }
    }

    /**
     * @param $id
     * @return int
     * @throws HttpException
     * @throws \Exception
     */
    public function actionDeleteComment($id)
    {
        $user_id = \Yii::$app->user->id;
        $comment = Comment::find()->where(['id' => $id, 'user_id' => $user_id])->one();

        if ($comment) {
            if ($comment->delete()) {
                return 1;
            } else {
                throw new HttpException(500, "Unable to delete comment.");
            }
        } else {
            throw new HttpException(400, "not allowed to perform this action.");
        }
    }
}
