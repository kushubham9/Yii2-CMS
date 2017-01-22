<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController;

/**
 * Spotlight Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class SpotlightController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\Spotlight' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index']);
        return $actions;
    }

    /**
     * @param $month
     * @param $year
     * @return mixed
     */
    public function actionIndex($month, $year)
    {
        $model = new $this->modelClass;
        $dataProvider = $model->spotlight($month, $year);
        return $dataProvider;
    }
}
