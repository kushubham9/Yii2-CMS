<?php

namespace api\modules\v1\controllers ;

use api\components\Controller ;
use Yii ;

/**
 * Search Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class SearchController extends Controller
{

    /**
     * @return array
     */
    public function actionIndex()
    {
        $qGet = Yii::$app->request->get('q') ;
        $type = Yii::$app->request->get('type') ;
        $query = [] ;
        switch ($type) {
            case 'user':
                $query = $this->_userSearch($qGet) ;
                break ;

            case 'project':
                $query = $this->_projectSearch($qGet) ;
                break ;
            case 'default' :
                break ;
        }
        return $query ;
    }

    /**
     * @param $qGet
     * @return array
     */
    private function _userSearch($qGet)
    {

        if (filter_var($qGet, FILTER_VALIDATE_EMAIL)) {
            $q = [
                "match" => [
                    "email" => [
                        'query' => $qGet,
                        "fuzziness" => 2
                    ]
                ]
            ] ;
        } else if (strpos($qGet, ':') !== false) {
            $qArr = explode(':', $qGet) ;
            $q = [
                "match" => [
                    $qArr[0] => $qArr[1]
                ]
            ] ;
        } else {
            $q = [
                "match" => [
                    "_all" => [
                        "query" => $qGet,
                        "fuzziness" => 'auto'
                    ]
                ]
            ] ;
        }
        $query = \common\models\elastic\UserElastic::find()->query($q) ;
        $response = $query->search() ;
        if ($response['hits']['total'] === 0) {
            return [] ;
        }
        $ids = [] ;
        foreach ($response['hits']['hits'] as $r) {
            $ids[] = $r->getPrimaryKey() ;
        }
        $orderBy = new \yii\db\Expression('FIELD (id, ' . implode(', ', $ids) . ')') ;
        $users = \common\models\User::find()->where(['id' => $ids, 'status' => \common\models\User::STATUS_ACTIVE])->orderBy([$orderBy])->all() ;
        $results = [] ;
        foreach ($users as $user) {
            $temp = ['user_id' => $user->id];
            $temp['details'] = $user->cardAttributes;
            $results[] = $temp;
        }

        return $results ;
    }

    /**
     * @param $qGet
     * @return array
     */
    private function _projectSearch($qGet)
    {

        if (filter_var($qGet, FILTER_VALIDATE_EMAIL)) {
            $q = [
                "match" => [
                    "email" => [
                        'query' => $qGet,
                        "fuzziness" => 2
                    ]
                ]
            ] ;
        } else if (strpos($qGet, ':') !== false) {
            $qArr = explode(':', $qGet) ;
            $q = [
                "match" => [
                    $qArr[0] => $qArr[1]
                ]
            ] ;
        } else {
            $q = [
                "multi_match" =>[
                    "query"=>    $qGet,
                    "fields"=> [ "title", "description", "creative_fields", "tags", "tools", "typeface", "user" ]
                ]
            ] ;
        }
        $query = \common\models\elastic\ProjectElastic::find()->limit(100)->query($q) ;
        $response = $query->search() ;
        if ($response['hits']['total'] === 0) {
            return [] ;
        }
        $ids = [] ;
        foreach ($response['hits']['hits'] as $r) {
            $ids[] = $r->getPrimaryKey() ;
        }
        $orderBy = new \yii\db\Expression('FIELD (id, ' . implode(', ', $ids) . ')') ;
        $projects = \common\models\Project::find()->where(['id' => $ids, 'status' => 2])->orderBy([$orderBy])->all() ;
        foreach ($projects as $n=>$project) {
            $results[$n] = $project->getIndexAttributes() ;
        }
        return $results ;
    }
}
