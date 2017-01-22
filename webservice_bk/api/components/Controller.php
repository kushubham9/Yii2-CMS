<?php

/**
 * Parents controller for all ones
 * Created by PhpStorm.
 * @author Wenceslaus DSilva <wenceslaus@indiefolio.com>
 * Date: 03.04.15
 * Time: 00:29
 * @inheritdoc
 */

namespace api\components ;

/**
 * Class Controller
 * @package api\components
 */
class Controller extends \yii\rest\Controller
{

    use \api\components\traits\ControllersCommonTrait ;

    /**
     * @var string
     */
    public $serializer = 'common\components\CustomSerializer' ;
}
