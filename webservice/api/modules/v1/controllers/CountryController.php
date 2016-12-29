<?php

namespace api\modules\v1\controllers ;

use api\components\ActiveController ;

/**
 * Country Controller API
 *
 * @category Controller
 * @package  Webservice
 * @author   Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * @license  http://www.gnu.org/copyleft/gpl.html GNU General Public License
 * @link     https://www.indiefolio.com
 */
class CountryController extends ActiveController
{

    /**
     * @var string
     */
    public $modelClass = 'common\models\CountryMaster' ;
}
