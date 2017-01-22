<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController;
use common\models\BackgroundJob;
use common\models\CreativeField;
use common\models\CreativeFieldMaster;
use common\models\Job;
use common\models\JobBid;
use common\models\JobBidProject;
use common\models\JobInvite;
use common\models\JobSkill;
use common\models\Project;
use common\models\User;
use linslin\yii2\curl\Curl;

/**
 * Jobs Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class JobsController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Job' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['create'], $actions['update'], $actions['view']);
        return $actions;
    }

    /**
     * @return mixed
     */
    public function actionJobList()
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->index() ;
        return $dataProvider ;
    }

    /**
     * @param int $type
     * @return mixed
     */
    public function actionMy($type = 0){
        $model = new $this->modelClass ;
        $dataProvider = $model->my($type) ;
        return $dataProvider ;
    }

    /**
     * @param $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function actionView($id)
    {
        $model = Job::find()->where(['job_guid' => $id])->one();
        return $model;
    }

    /**
     * @return array|JobBid
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionApply()
    {
        $request = \Yii::$app->request;
        $connection = \Yii::$app->db ;
        $postData = $request->post();
        $jobModel = Job::find()->where(['id'=>$postData['job_id'],'status'=>2])->andWhere(['>=','bids_ends',date('Y-m-d H:i:s')])->one();
        if(empty($jobModel)) {
            throw new \yii\web\HttpException(400, 'Sorry job listing has expired') ;
        }
        $model = new JobBid;
        $postData['user_id'] = \Yii::$app->user->id;
        $postData['status'] = 2;
        if (isset($postData['projects'])) {
            $projects = $postData['projects'];
            unset($postData['projects']);
        }

        $transaction = $connection->beginTransaction();
        try{
            if($model->load(['JobBid' => $postData]) && $model->save()){
                $jobModel->bid_count += 1;

                if($jobModel->save()) {
                    $model->refresh();
                    if (!empty($projects)) {
                        foreach ($projects as $project) {
                            if (Project::find()->where(['id' => $project['id']])->exists()) {
                                $pTag = new JobBidProject;
                                $pTag->job_bid_id = $model->id;
                                $pTag->project_id = $project['id'];
                                $pTag->status = 0;
                                if (!$pTag->save()) {
                                    throw new \yii\web\HttpException(400, 'Error saving job projects.');
                                }
                            }
                        }
                    }
                }
                $transaction->commit() ;

                \Yii::$app->backgroundJob->add('mail/job-bid', BackgroundJob::TYPE_JOB_BID_MAIL, $postData);
                return $model->getIndexAttributes();
            } else {
                throw new \yii\web\HttpException(400, 'Error saving your application. Please try again') ;
            }
        } catch (\Exception $e) {
            $transaction->rollBack() ;
            throw new \yii\web\HttpException(400, 'Something went wrong. Please try again.') ;
        }
    }

    /**
     * @return int
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionInvite(){
        $request = \Yii::$app->request;
        $connection = \Yii::$app->db ;

        $postData = $request->post();
        $postData['user_id'] = \Yii::$app->user->id;
        $postData['status'] = 2;

        $transaction = $connection->beginTransaction();
        try{
            if (isset($postData['invited_users'])) {
                $invited_users = $postData['invited_users'];
                unset($postData['invited_users']);
                foreach ($invited_users as $to_user_id) {
                    $postData['to_user_id'] = $to_user_id;
                    if(User::find()->where(['id' => $to_user_id])->exists()){
                        $model = new JobInvite;
                        if (!($model->load(['JobInvite' => $postData]) && $model->save())) {
                            throw new \yii\web\HttpException(400, 'Unable to invite users.') ;
                        }
                    } else {
                        throw new \yii\web\HttpException(400, 'Inviting invalid users.') ;
                    }
                }
                $transaction->commit();

                $mailParams =$request->post();
                $mailParams['user_id'] = \Yii::$app->user->id;

                \Yii::$app->backgroundJob->add('mail/job-invite', BackgroundJob::TYPE_JOB_INVITE_MAIL, $mailParams);
                return 1;
            } else{
                throw new \yii\web\HttpException(400, 'No users to invites.') ;
            }
        } catch (\Exception $e) {
            $transaction->rollBack() ;
            throw new \yii\web\HttpException(400, $e->getMessage()) ;
        }
        return 1;
    }

    /**
     * @return mixed
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $job_data = $request->post();
        $creative_fields = isset($job_data['creative_fields']) ? $job_data['creative_fields'] : [];
        $skills = isset($job_data['skills']) ? $job_data['skills'] : [];

        if(isset($job_data['creative_fields'])){
            unset($job_data['creative_fields']);
        }

        if(isset($job_data['skills'])){
            unset($job_data['skills']);
        }

        $job_data['status'] = 2;
        $job_data['budget_type'] = 0;
        $job_data['user_id'] = \Yii::$app->user->id;
        $bids_ends = explode('/', $job_data['bids_ends']);
        $bids_ends = array_reverse($bids_ends);
        $job_data['bids_ends'] = implode('-', $bids_ends) . " 23:59:59";

        if (isset($job_data['job_guid']) && !is_null($job_data['job_guid'])) {
            $model = \Yii::$app->user->identity->getJobs()->where(['job_guid' => $job_data['job_guid']])->one();
        } else {
            $model = new $this->modelClass;
        }

        if(!$model){
            throw new \yii\web\HttpException(400, 'Invalid Request') ;
            return;
        }

        $connection = \Yii::$app->db ;
        $transaction = $connection->beginTransaction();

        try{
            if($model->load(['Job' => $job_data]) && $model->save()){
                $model->refresh();

                $job_id = $model->id;
                $entity_type = $model->_entity_type;

                CreativeField::deleteAll(['entity_type' => $entity_type, 'entity_id' => $job_id]);
                if (!empty($creative_fields)) {
                    foreach ($creative_fields as $id) {
                        if (CreativeFieldMaster::find()->where(['id' => $id])->exists()) {
                            $pTag = new CreativeField ;
                            $pTag->entity_type = $entity_type ;
                            $pTag->entity_id = $model->id ;
                            $pTag->creative_field_master_id = $id ;
                            $pTag->status = 1 ;
                            if (!$pTag->save()) {
                                throw new \yii\web\HttpException(400, 'Error saving creative fields.') ;
                            }
                        }
                    }
                }

                JobSkill::deleteAll(['job_id' => $job_id]);
                if (!empty($skills)) {
                    foreach ($skills as $skill) {
                        if(isset($skill['name'])){
                            $pTag = new JobSkill ;
                            $pTag->name = $skill['name'] ;
                            $pTag->job_id = $model->id ;
                            $pTag->status = 2 ;
                            if (!$pTag->save()) {
                                throw new \yii\web\HttpException(400, 'Error saving skills.') ;
                            }
                        }
                    }
                }
                $transaction->commit() ;
                $curl = new Curl();
                $params = json_encode([
                    "prerenderToken" => "FuBAof63KUCBXRRnXKS3",
                    "url" => "https://www.indiefolio.com/jobs/" . $model->slug
                ]);
                $curl->setOption(CURLOPT_POSTFIELDS, $params)->setOption(CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)))->post('https://api.prerender.io/recache');
                return $model;
            } else {
                throw new \yii\web\HttpException(405, "Error saving job.") ;
            }
        } catch (\Exception $e) {
            $transaction->rollBack() ;
            throw new \yii\web\HttpException(405, $e->getMessage()) ;
        }
    }

    /**
     * @return int
     * @throws \yii\web\HttpException
     */
    public function actionRemoveBid(){
        $user_id = \Yii::$app->user->id;
        $request = \Yii::$app->request;
        $bid_id = $request->post('bid_id', null);

        if (!is_null($bid_id)) {
            $bid = JobBid::find()->where(['id' => $bid_id])->one();
            if (!is_null($bid)) {
                $job = $bid->job;
                if(Job::find()->where(['id' => $job->id, 'user_id' => $user_id])->exists() || $bid->user_id == $user_id){
                    $bid->status = -1;
                    if ($bid->save()) {
                        return 1;
                    } else {
                        throw new \yii\web\HttpException(500, 'Unable to remove bid.') ;
                    }
                } else {
                    throw new \yii\web\HttpException(405, 'Not authorized to perform this action.') ;
                }
            } else {
                throw new \yii\web\HttpException(400, 'Invalid bid specified.') ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Bid not specified.') ;
        }
    }

    /**
     * @return int
     * @throws \Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionDeleteBid(){
        $user_id = \Yii::$app->user->id;
        $request = \Yii::$app->request;
        $bid_id = $request->post('bid_id', null);
        $connection = \Yii::$app->db;

        if (!is_null($bid_id)) {
            $bid = JobBid::find()->where(['id' => $bid_id, 'user_id' => $user_id])->one();
            if (!is_null($bid)) {
                $transaction = $connection->beginTransaction();
                JobBidProject::deleteAll(['job_bid_id' => $bid_id]);
                if ($bid->delete()) {
                    $transaction->commit();
                    return 1;
                } else {
                    $transaction->rollBack();
                    throw new \yii\web\HttpException(500, 'Unable to delete bid.') ;
                }
            } else {
                throw new \yii\web\HttpException(405, 'Not authorized to perform this action.') ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Bid not specified.') ;
        }
    }

    /**
     * @return int
     * @throws \Exception
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionDeleteJob(){
        $user_id = \Yii::$app->user->id;
        $request = \Yii::$app->request;
        $job_id = $request->post('job_id', null);
        $connection = \Yii::$app->db;

        if (!is_null($job_id)) {
            $job = Job::find()->where(['job_guid' => $job_id, 'user_id' => $user_id])->one();
            if (!is_null($job)) {
                $transaction = $connection->beginTransaction();
                CreativeField::deleteAll(['entity_type' => $job->_entity_type, 'entity_id' => $job->id]);
                JobInvite::deleteAll(['job_id' => $job->id]);
                JobSkill::deleteAll(['job_id' => $job->id]);
                foreach ($job->getJobBids()->each(100) as $bid) {
                    JobBidProject::deleteAll(['job_bid_id' => $bid->id]);
                    if (!$bid->delete()) {
                        $transaction->rollBack();
                        throw new \yii\web\HttpException(500, 'Unable to delete job bids.') ;
                    }
                }
                if ($job->delete()) {
                    $transaction->commit();
                    return 1;
                } else {
                    $transaction->rollBack();
                    throw new \yii\web\HttpException(500, 'Unable to delete job.') ;
                }
            } else {
                throw new \yii\web\HttpException(405, 'Not authorized to perform this action.') ;
            }
        } else {
            throw new \yii\web\HttpException(400, 'Job not specified.') ;
        }
    }

}
