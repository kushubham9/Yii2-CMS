<?php

namespace api\modules\v1\controllers;

use api\components\ActiveController;
use common\models\BackgroundJob;
use common\models\Project;
use common\models\ProjectBody;
use common\models\ProjectDetail;
use common\models\Tag;
use common\models\TagMaster;
use common\models\Tool;
use common\models\ToolMaster;
use common\models\Typeface;
use common\models\TypefaceMaster;
use common\models\CreativeField;
use common\models\CreativeFieldMaster;
use common\models\JobBidProject;
use common\models\Applaud;
use common\models\Bookmark;
use common\models\Comment;
use ColorThief\ColorThief;
use common\components\Utility;
use linslin\yii2\curl\Curl;
use Yii;
use yii\base\Response;
use \yii\web\HttpException;
use \yii\helpers\Json;
use \yii\web\BadRequestHttpException;

/**
 * Project Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class ProjectsController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Project';

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['view']);
        unset($actions['create']);
        return $actions;
    }

    /**
     * @param $id
     * @return int
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function actionDelete($id)
    {
        $project = \Yii::$app->user->identity->getProjects()->where(['id' => $id])->one();
        if ($project) {
            $connection = \Yii::$app->db;
            $transaction = $connection->beginTransaction();
            try {
                $entity_type = $project->_entity_type;
                $project_id = $project->id;
                ProjectDetail::deleteAll(['project_id' => $project_id]);
                ProjectBody::deleteAll(['project_id' => $project_id]);

                CreativeField::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);
                Tag::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);
                Tool::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);
                Typeface::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);

                Applaud::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);
                Bookmark::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);
                Comment::deleteAll(['entity_type' => $entity_type,
                    'entity_id' => $project_id]);

                JobBidProject::deleteAll(['project_id' => $project_id]);

                if ($project->delete()) {
                    $transaction->commit();
                    return 1;
                } else {
                    throw new HttpException(400, 'Unable to delete project');
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw new HttpException(405, $e->getMessage());
            }
        } else {
            throw new HttpException(400, 'Not authorized to delete');
        }
    }

    /**
     * @return mixed
     */
    public function actionExplore()
    {
        $model = new $this->modelClass;
        $dataProvider = $model->explore();
        return $dataProvider;
    }

    /**
     * @return mixed
     */
    public function actionMy()
    {
        return \Yii::$app->user->identity->minimalProjectAttributes;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionView($id)
    {
//        $title = Yii::$app->request->get('p');
        $model = new $this->modelClass;
        if (strpos($id, '-') !== false) {
            $query = ['project.slug' => $id];
        } else {
            $query = ['project.project_guid' => $id];
        }
        $dataProvider = $model->details($query);

        $viewCount = Project::findOne($query);

        if ($viewCount->user_id !== \Yii::$app->user->id) {
            $viewCount->view_count = (int)$viewCount->view_count + 1;
            $viewCount->save();
        }
        $viewCount->refresh();
//        if ($title !== $viewCount->slug) {
//            return $this->redirect('http://front.bbv2.dev/project/'.$viewCount->project_guid.'/'.$viewCount->slug, 301);
//            Yii::$app->response->redirect('http://front.bbv2.dev/project/'.$viewCount->project_guid.'/'.$viewCount->slug, 301, true);
//            $data = ['status' => 301, 'url' => '/' .$viewCount->project_guid . '/' . $viewCount->slug];
//            return $data;
//        }
        return $dataProvider;
    }

    /**
     * @param $id
     * @return mixed
     * @throws HttpException
     */
    public function actionViewForEdit($id)
    {
        $project = \Yii::$app->user->identity->getProjects()->where(['project_guid' => $id])->one();

        if ($project) {
            return $project->editAttributes;
        } else {
            throw new HttpException(400, 'Invalid Request');
        }
    }

    /**
     * @return mixed
     * @throws HttpException
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $request = \Yii::$app->request;
        $connection = \Yii::$app->db;

        // Getting the project Details
        $project = $request->post('project');
        $project['user_id'] = \Yii::$app->user->id;
        $isNewRec = 0;


        if (isset($project['project_guid']) && !is_null($project['project_guid'])) {
            $model = \Yii::$app->user->identity->getProjects()->where(['project_guid' => $project['project_guid']])->one();
        } else {
            $model = new $this->modelClass;
            $isNewRec = 1;
        }

        if (!$model) {
            throw new HttpException(400, 'Invalid Request');
        }

        if (isset($project["cover_image"])) {
            $filename_splits = explode('/', is_array($project["cover_image"]) ? $project["cover_image"][0] : $project["cover_image"]);
            $project["cover_image"] = $filename_splits[count($filename_splits) - 1];
        } else {
            $project["cover_image"] = "project_cover_placeholder.jpg";
        }

        if ($project['status'] == 2 && ($isNewRec === 0 || is_null($model->published_at))) {
            $project['published_at'] = date('Y-m-d H:i:s');
        }
        
        $transaction = $connection->beginTransaction();
        try {
            if ($model->load(['Project' => $project]) && $model->save()) {  // Saving a project model
                $model->refresh();

                $project_id = $model->id;
                $entity_type = $model->_entity_type;

                if ($this->_updateProjectDetails($project_id) && 
                    $this->_updateCreativeFields($project_id, $entity_type) &&
                    $this->_updateTag($project_id, $entity_type) &&
                    $this->_updateTools($project_id, $entity_type) && 
                    $this->_updateTypeface($project_id, $entity_type) &&
                    $this->_updateProjectBody($project_id)
                )
                
                {
                    $transaction->commit(); /*Commit the transaction*/

                    $params = [
                        'project_id' => $project_id,
                        'project_body_data' => \Yii::$app->request->post('project_body')
                    ];

                    Yii::$app->backgroundJob->add('colorthief/update-project-body', BackgroundJob::TYPE_COLORTHIEF, $params);

                    if ($model->status === 2) {
                        $params = [
                            "actor" => "user:" . \Yii::$app->user->id,
                            "verb" => ($isNewRec) ? 'Created' : 'Edited',
                            "object" => "Project:" . $model->id,
                            'user_id' => Yii::$app->user->id
                        ];

                        Yii::$app->backgroundJob->add('stream/record-activity', BackgroundJob::TYPE_RECORD_ACTIVITY, $params);
                    }
                    
                    $model->save(false);
                    $model->refresh();
                    
                    if ($model->status == 2) {
                        $curl = new Curl();
                        $params = json_encode([
                            "prerenderToken" => "FuBAof63KUCBXRRnXKS3",
                            "url" => "https://www.indiefolio.com/project/" . $model->slug
                        ]);
                        $curl->setOption(CURLOPT_POSTFIELDS, $params)->setOption(CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)))->post('https://api.prerender.io/recache');
                    }

                    return $model;
                }
                    
                
                else {
                    throw new HttpException(400, 'Project Details can not be saved');
                }
            } else {
                throw new HttpException(400, 'Project cannot be saved');
            }
        } catch (HttpException $e) {
            $transaction->rollBack();
            throw new HttpException(405, $e->getMessage());
        }
    }

    private function _updateProjectDetails($project_id)
    {
        $project_details = \Yii::$app->request->post('project_details');
        $project_details['project_id'] = $project_id;
        $project_details['divider'] = json_encode($project_details['divider']);

        if (isset($project_details['background'])) {
            if (isset($project_details['background']['BackgroundImage'])) {
                $filename_splits = explode('/', $project_details['background']['BackgroundImage']);
                $project_details['background']['BackgroundImage'] = $filename_splits[count($filename_splits) - 1];
            }
            $project_details['background'] = json_encode($project_details['background']);
        }

        ProjectDetail::deleteAll(['project_id' => $project_id]);
        
        $p_detail_model = new ProjectDetail;
        if ($p_detail_model->load(['ProjectDetail' => $project_details]) && $p_detail_model->save())
            return true;

        return false;
    }


    private function _updateCreativeFields($project_id, $entity_type)
    {
        CreativeField::deleteAll(['entity_type' => $entity_type, 'entity_id' => $project_id]);

        $creative_fields = \Yii::$app->request->post('creative_fields');
        if (!empty($creative_fields)) {
            foreach ($creative_fields as $cField) {
                
                if (isset($cField['id'])) {
                    if (CreativeFieldMaster::find()->where(['id' => $cField['id']])->exists()) {
                        $pTag = new CreativeField;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->creative_field_master_id = $cField['id'];
                        $pTag->status = 1;
                        
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Creative Fields cannot be saved');
                            return false;
                        }
                    }
                } 

                elseif (isset($cField['name'])) {
                    throw new HttpException(400, json_encode(['msg' => 'Please select creative fields from list']));
                    return false;
                }
            }
        }
        return true;
    }

    private function _updateTag($project_id, $entity_type)
    {
        Tag::deleteAll(['entity_type' => $entity_type, 'entity_id' => $project_id]);
        
        $tags = \Yii::$app->request->post('tags');
        if (!empty($tags)) {
            foreach ($tags as $cTag) {
                if (isset($cTag['id'])) {
                    if (TagMaster::find()->where(['id' => $cTag['id']])->exists()) {
                        $pTag = new Tag;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->tag_master_id = $cTag['id'];
                        $pTag->status = 1;
                        
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Tags cannot be saved');
                            return false;
                        }
                    }
                } 

                elseif (isset($cTag['name'])) {
                    $pMaster = new TagMaster;
                    $pMaster->name = $cTag['name'];
                    $pMaster->status = 1;
                    if ($pMaster->save()) {
                        $pMaster->refresh();
                        $pTag = new Tag;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->tag_master_id = $pMaster->id;
                        $pTag->status = 1;
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Tags cannot be saved');
                            return false;
                        }
                    } else {
                        throw new HttpException(400, 'Tags cannot be created');
                        return false;
                    }
                }
            }
        }

        return true;
    }


    private function _updateTools($project_id, $entity_type)
    {
        Tool::deleteAll(['entity_type' => $entity_type,
                        'entity_id' => $project_id]);
        $tools = \Yii::$app->request->post('tools');
        if (!empty($tools)) {
            foreach ($tools as $cTool) {
                if (isset($cTool['id'])) {
                    if (ToolMaster::find()->where(['id' => $cTool['id']])->exists()) {
                        $pTag = new Tool;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->tool_master_id = $cTool['id'];
                        $pTag->status = 1;
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Tools cannot be saved');
                            return false;
                        }
                    }
                } 
                elseif (isset($cTool['name'])) {
                    $tMaster = new ToolMaster;
                    $tMaster->name = $cTool['name'];
                    $tMaster->status = 1;
                    if ($tMaster->save()) {
                        $tMaster->refresh();

                        $pTag = new Tool;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->tool_master_id = $tMaster->id;
                        $pTag->status = 1;
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Tools cannot be saved');
                            return false;
                        }
                       
                    } else {
                        throw new HttpException(400, 'Tools cannot be created');
                    }
                }
            }
        }
        return true;
    }

    private function _updateTypeface($project_id, $entity_type)
    {
        Typeface::deleteAll(['entity_type' => $entity_type,
                        'entity_id' => $project_id]);
        $typefaces = \Yii::$app->request->post('typefaces');
        if (!empty($typefaces)) {
            foreach ($typefaces as $cType) {
                if (isset($cType['id'])) {
                    if (TypefaceMaster::find()->where(['id' => $cType['id']])->exists()) {
                        $pTag = new Typeface;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->typeface_master_id = $cType['id'];
                        $pTag->status = 1;
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'TypeFaces cannot be saved');
                            return false;
                        }
                    }
                } 

                elseif (isset($cType['name'])) {
                    $tMaster = new TypefaceMaster;
                    $tMaster->name = $cType['name'];
                    $tMaster->status = 1;
                    if ($tMaster->save()) {
                        $tMaster->refresh();
                        $pTag = new Typeface;
                        $pTag->entity_type = $entity_type;
                        $pTag->entity_id = $project_id;
                        $pTag->typeface_master_id = $tMaster->id;
                        $pTag->status = 1;
                        if (!$pTag->save()) {
                            throw new HttpException(400, 'Typeface cannot be saved');
                            return false;
                        }
                       
                    } else {
                        throw new HttpException(400, 'Typeface cannot be created');
                        return false;
                    }
                }
            }
        }
        return true;
    }


    private function _updateProjectBody($project_id)
    {
        ProjectBody::deleteAll(['project_id' => $project_id]);

        $dominantColor = [];
        $project_body_data = \Yii::$app->request->post('project_body');
        foreach ($project_body_data as $pbData) {
            $pBody = new ProjectBody;
            $pBody->order = $pbData['order'];
            $pBody->entity_type = $pbData['entity_type'];
            $pBody->project_id = $project_id;
            $pBody->status = 2;

            $pBody->is_optimized = 0;
            if ($pbData['entity_type'] == 'image') {
                $cover_image_path = is_array($pbData['entity_data']) ? $pbData['entity_data'][2] : $pbData['entity_data'];

                if (!Utility::remoteFileExists($cover_image_path)) {
                    $cover_image_path = basename(is_array($pbData['entity_data']) ? $pbData['entity_data'][2] : $pbData['entity_data']);
                }

                $filename_splits = explode('/', $cover_image_path);
                $pBody->entity_data = $filename_splits[count($filename_splits) - 1];
            } 

            else {
                $pBody->entity_data = $pbData['entity_data'];
            }
            
            if (!$pBody->save()) {
                return false;
            }

            else{
                $project_body_id[] = $pBody->id;
            } 
        }

        /*Scheduling a background job to see if the images are optimized.*/
        $params = [
            'project_body_id'=>$project_body_id
        ];
        
        Yii::$app->backgroundJob->add('imageutil/is-optimal', BackgroundJob::TYPE_IMAGEUTIL, $params);

        return true;
    }

    /**
     * @return array
     */
    public function actionRandomProject()
    {
        $model = Project::find()->joinWith(['user', 'projectBodiesHome'])->limit(2)->orderBy('RAND()')->all();
        $arr = [];
        foreach ($model as $m) {
            $arr[] = $m->getHomeAttributes();
        }
        return $arr;
    }

    /**
     * @param int $count
     * @return mixed
     */
    public function actionRecommendedProjects($count = 6)
    {
        $model = new $this->modelClass;
        $dataprovider = $model->getRecommendedProjects($count);
        return $dataprovider;
    }

    /**
     * @return mixed
     */
    public function actionSlideProjects()
    {
        $model = new $this->modelClass;
        $dataprovider = $model->slideProjects();
        return $dataprovider;
    }

    /**
     * @return int
     * @throws BadRequestHttpException
     * @throws HttpException
     */
    public function actionPublishStatus()
    {
        $request = \Yii::$app->request;
        $project_id = $request->post('project_id', null);
        $status = $request->post('status', null);
        if (!is_null($project_id) && !is_null($status)) {
            $project = \Yii::$app->user->identity->getProjects()->where(['id' => $project_id])->one();
            if ($project) {
                $project->status = $status;
                if ($project->save()) {
                    $project->refresh();
                    if ($project->status === 2) {
                        $curl = new Curl();
                        $params = json_encode([
                            "prerenderToken" => "FuBAof63KUCBXRRnXKS3",
                            "url" => "https://www.indiefolio.com/project/" . $project->project_guid
                        ]);
                        $curl->setOption(CURLOPT_POSTFIELDS, $params)->setOption(CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($params)))->post('https://api.prerender.io/recache');
                    }
                    return 1;
                } else {
                    throw new HttpException(400, 'Project status can not be changed.');
                }
            } else {
                throw new HttpException(400, 'Not authorized to change project status.');
            }
        } else {
            throw new BadRequestHttpException("Missing request parameter: project_id or status", 1);
        }
    }

 

    /**
     * @return mixed
     */
    public function actionRecommendedProject()
    {
        $model = new $this->modelClass;
        $dataprovider = $model->getRecommendedProject();
        return $dataprovider;
    }
}