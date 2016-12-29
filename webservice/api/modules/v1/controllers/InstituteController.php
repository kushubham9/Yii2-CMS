<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;
use yii\helpers\ArrayHelper;

/**
 * Institute Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class InstituteController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = 'common\models\Institute' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions() ;
        unset($actions['view']) ;
        return $actions ;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = new $this->modelClass ;
        $dataProvider = $model->instituteDetails($id) ;
        return $dataProvider ;
    }

    /**
     * @return array
     */
    public function actionDropDown()
    {
        return ArrayHelper::map(\common\models\Institute::find()->where(['status' => 2])->all(), 'id', 'name');
    }
}
