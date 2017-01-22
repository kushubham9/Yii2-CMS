<?php

namespace api\modules\v1\controllers ;

use common\models\Country;
use common\models\State;
use common\models\TmpCoupon;
use common\models\TmpRecruiter;
use Yii ;
use api\components\Controller ;
use yii\data\ArrayDataProvider ;
use yii\db\Exception;
use yii\db\Query ;
use yii\helpers\ArrayHelper ;
use yii\web\HttpException;

/**
 * Common Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class CommonController extends Controller
{

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchTermList($q = "")
    {
        $query = new Query ;
        $provider = new ArrayDataProvider([
            'allModels' => $query->select(['distinct(name)',
                'id'])
            ->from(['tag_master'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $tags = $provider->getModels() ;

        // $query1 = new Query ;
        // $provider1 = new ArrayDataProvider([
        //     'allModels' => $query1->select(['distinct(name)', 'id'])
        //                     ->from(['creative_field_master'])
        //                     ->andFilterWhere(['like', 'name', $q])
        //                     ->limit(5)
        //                     ->all(),
        // ]) ;
        // $creative_fields = $provider1->getModels() ;
        // $query2 = new Query ;
        // $provider2 = new ArrayDataProvider([
        //     'allModels' => $query2->select(['distinct(name)', 'id'])
        //                     ->from(['tool_master'])
        //                     ->andFilterWhere(['like', 'name', $q])
        //                     ->limit(5)
        //                     ->all(),
        // ]) ;
        // $tools = $provider2->getModels() ;
        // $query3 = new Query ;
        // $provider3 = new ArrayDataProvider([
        //     'allModels' => $query3->select(['distinct(name)', 'id'])
        //                     ->from(['typeface_master'])
        //                     ->andFilterWhere(['like', 'name', $q])
        //                     ->limit(5)
        //                     ->all(),
        // ]) ;
        // $typefaces = $provider3->getModels() ;

        $response = [] ;

        foreach ($tags as $tag) {
            $response[] = array_merge($tag, ['entity_type' => 'tag']) ;
        }

        // foreach ($creative_fields as $creative_field) {
        //     $response[] = array_merge($creative_field, ['entity_type' => 'creative_field']);
        // }
        // foreach ($tools as $tool) {
        //     $response[] = array_merge($tool, ['entity_type' => 'tool']);
        // }
        // foreach ($typefaces as $typeface) {
        //     $response[] = array_merge($typeface, ['entity_type' => 'typeface']);
        // }

        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchCreativeFields($q = "")
    {
        $query1 = new Query ;
        $provider1 = new ArrayDataProvider([
            'allModels' => $query1->select(['distinct(name)',
                'id'])
            ->from(['creative_field_master'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $creative_fields = $provider1->getModels() ;

        $response = [] ;
        foreach ($creative_fields as $creative_field) {
            $response[] = array_merge($creative_field, ['entity_type' => 'creative_field']) ;
        }
        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchExpertises($q = "")
    {
        $query1 = new Query ;
        $provider1 = new ArrayDataProvider([
            'allModels' => $query1->select(['distinct(name)',
                'id'])
            ->from(['expertise_master'])->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $creative_fields = $provider1->getModels() ;

        $response = [] ;
        foreach ($creative_fields as $creative_field) {
            $response[] = array_merge($creative_field, ['entity_type' => 'expertise']) ;
        }
        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchTags($q = "")
    {
        $query = new Query ;
        $provider = new ArrayDataProvider([
            'allModels' => $query->select(['distinct(name)',
                'id'])
            ->from(['tag_master'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $tags = $provider->getModels() ;

        $response = [] ;
        foreach ($tags as $tag) {
            $response[] = array_merge($tag, ['entity_type' => 'tag']) ;
        }
        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchTools($q = "")
    {
        $query2 = new Query ;
        $provider2 = new ArrayDataProvider([
            'allModels' => $query2->select(['distinct(name)',
                'id'])
            ->from(['tool_master'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $tools = $provider2->getModels() ;

        $response = [] ;
        foreach ($tools as $tool) {
            $response[] = array_merge($tool, ['entity_type' => 'tool']) ;
        }
        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchTypefaces($q = "")
    {
        $query3 = new Query ;
        $provider3 = new ArrayDataProvider([
            'allModels' => $query3->select(['distinct(name)',
                'id'])
            ->from(['typeface_master'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $typefaces = $provider3->getModels() ;

        $response = [] ;
        foreach ($typefaces as $typeface) {
            $response[] = array_merge($typeface, ['entity_type' => 'typeface']) ;
        }
        return $response ;
    }

    /**
     * @return array
     */
    public function actionTopCreativeFields()
    {
        $arr = [] ;
        $creative_fields = \common\models\CreativeField::find()
        ->select(['creative_field.creative_field_master_id'])
        ->joinWith(['creativeFieldMaster'])
        ->andFilterWhere(['creative_field.entity_type' => 'Project'])
        ->groupBy(['creative_field.creative_field_master_id'])
        ->orderBy(['count(creative_field.creative_field_master_id)' => SORT_DESC])
        ->limit(20)
        ->all() ;

        foreach ($creative_fields as $c) {
            $arr[] = [
                'id' => $c->creative_field_master_id,
                'name' => $c->creativeFieldMaster->name,
                'entity_type' => 'creative_field'
            ] ;
        }
        return $arr ;
    }

    /**
     * @param string $q
     * @param bool $detailedOjbect
     * @param int $count
     * @return array
     */
    public function actionSearchUserList($q = "", $detailedOjbect = false, $count = 5)
    {
        $arr = [] ;
        $q = explode(" ", $q) ;
        $fq = $q[0] ;
        $lq = (count($q) > 1) ? $q[1] : $q[0] ;

        $query = \common\models\User::find() ;
        if (count($q) > 1) {
            $query->andFilterWhere(['like',
                'first_name',
                $fq])
            ->andFilterWhere(['like',
                'last_name',
                $lq])
            ->orFilterWhere(['like',
                'username',
                $fq])
            ->orderBy("case when (first_name LIKE '%{$fq}%' AND last_name LIKE '%{$lq}%') then 1 when first_name LIKE '%{$fq}%' then 2 when last_name LIKE '%{$lq}%' then 3 else 4 end");
        } else {
            $query->orFilterWhere(['like',
                'first_name',
                $fq])
            ->orFilterWhere(['like',
                'last_name',
                $fq])
            ->orFilterWhere(['like',
                'username',
                $fq]) ;
        }

        $users = $query->limit($count)->all() ;

        foreach ($users as $user) {
            if (!$detailedOjbect) {
                $user_name = $user->first_name . " " . $user->last_name ;
                if (strlen($user_name) < 2) {
                    $user_name = $user->username ;
                }
                $arr[] = [
                    'id' => $user->id,
                    'username' => $user_name
                ] ;
            } else {
                $user_data = $user->indexAttributes ;
                $user_data['selected'] = false ;
                $arr[] = $user_data ;
            }
        }
        return $arr ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchJobSkillSuggestion($q = "")
    {
        $query3 = new Query ;
        $provider3 = new ArrayDataProvider([
            'allModels' => $query3->select(['distinct(name)'])
            ->from(['job_skill'])
            ->andFilterWhere(['like',
                'name',
                $q])
            ->limit(5)
            ->all(),
        ]) ;
        $skills = $provider3->getModels() ;

        $response = [] ;
        foreach ($skills as $skill) {
            $response[] = array_merge($skill, ['entity_type' => 'job_skill']) ;
        }
        return $response ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchLocations($q = ""){
        $arr = [] ;
        $query = new \yii\db\Query ;

        $query->select('distinct(job_location)')
            ->from('job')
            ->andFilterWhere(['LIKE', 'job_location', $q]) ;

        $locations = $query->limit(10)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = $location['job_location'];
        }
        return $arr ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchJobLocation($q = "")
    {
        $arr = [] ;
        $query = new \yii\db\Query ;
        $cur_time = new \DateTime("now", new \DateTimeZone("Asia/Calcutta")) ;

        $query->select('distinct(job_location)')
        ->from('job')
        ->where(['status' => 2])
        ->andFilterWhere(['>',
            'bids_ends',
            $cur_time->format('Y-m-d H:i:s')])
        ->andFilterWhere(['LIKE',
            'job_location',
            $q]) ;

        $locations = $query->limit(5)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = [
                'location' => $location['job_location']
            ] ;
        }
        return $arr ;
    }

    /**
     * @param string $q
     * @return array
     */
    public function actionSearchJobCompany($q = "")
    {
        $arr = [] ;
        $query = new \yii\db\Query ;
        $cur_time = new \DateTime("now", new \DateTimeZone("Asia/Calcutta")) ;

        $query->select('distinct(company_name)')
        ->from('job')
        ->where(['status' => 2])
        ->andFilterWhere(['>',
            'bids_ends',
            $cur_time->format('Y-m-d H:i:s')])
        ->andFilterWhere(['LIKE',
            'company_name',
            $q]) ;

        $locations = $query->limit(5)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = [
                'company' => $location['company_name']
            ] ;
        }
        return $arr ;
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionSocialLinkTypes()
    {
        return \common\models\SocialType::find()->all();
    }

    public function actionCountry($q = "") {

        $arr = [] ;
        $query = new \yii\db\Query ;

        $query->select('distinct(name)')
            ->from('country')
            ->andFilterWhere(['LIKE',
                'name',
                $q]) ;

        $locations = $query->limit(5)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = $location['name'];
        }
        return $arr ;
    }


    public function actionState($q = "") {
        $country = Yii::$app->request->get('country_id',null);
        $arr = [] ;
        $query = new \yii\db\Query ;

        $query->select('distinct(state.name)')
            ->from('state')
            ->andFilterWhere(['LIKE',
                'state.name',
                $q]) ;
        if (!is_null($country)){
            $query->join('Left JOIN',Country::tableName(),'country.id=state.country_id')->andFilterWhere(['LIKE',
                'country.name',
                $country]) ;
        }
        $locations = $query->limit(5)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = $location['name'];
        }
        return $arr ;
    }


    public function actionCity($q = "") {
        $country = Yii::$app->request->get('state_id',null);
        $arr = [] ;
        $query = new \yii\db\Query ;

        $query->select('distinct(city.name)')
            ->from('city')
            ->andFilterWhere(['LIKE',
                'city.name',
                $q]) ;
        if (!is_null($country)){
            $query->join('Left JOIN',State::tableName(),'state.id=city.state_id')->andFilterWhere(['LIKE',
                'state.name',
                $country]) ;
        }
        $locations = $query->limit(5)->all() ;

        foreach ($locations as $key => $location) {
            $arr[] = $location['name'];
        }
        return $arr ;
    }
    public function actionSoftware($q = "") {

        $query1 = new Query ;
        $provider1 = new ArrayDataProvider([
            'allModels' => $query1->select(['distinct(name)',
                'id'])
                ->from(['software_master'])->andFilterWhere(['like',
                    'name',
                    $q])
                ->limit(5)
                ->all(),
        ]) ;
        $softwares = $provider1->getModels() ;

        $response = [] ;
        foreach ($softwares as $software) {
            $response[] = array_merge($software, ['rating'=>1]) ;
        }
        return $response ;
    }

    public function actionRecruiter(){
        $coupon_code = Yii::$app->request->post('coupon',null);
        $post['TmpRecruiter'] = Yii::$app->request->post('TmpRecruiter',null);
        try {
            if(!is_null($coupon_code)) {
                $coupon = TmpCoupon::find()->where(['coupon'=>$coupon_code])->one();

                if(empty($coupon)) {
                    throw new HttpException(400,'Coupon does not exists or has expired');
                }
                $post['TmpRecruiter']['coupon_id'] = $coupon->id;
            }
            $model = new TmpRecruiter();
            if($model->load($post) && $model->save()) {
                return true;
            }
        } catch (Exception $ex) {
            throw new HttpException(400,$ex->getMessage());
        }
    }
    public function actionCoupon() {
        return TmpCoupon::find()->where(['coupon'=>Yii::$app->request->get('coupon',null)])->exists();
    }
}
