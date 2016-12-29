<?php

/**
 * Parents controller for all ones
 *
 * @author Wenceslaus Dsilva <wenceslaus@indiefolio.com>
 * Date: 03.04.15
 * Time: 00:29
 * @inheritdoc
 */

namespace api\components ;

/**
 * Class ActiveController
 * @package api\components
 * @inheritdoc
 */
class ActiveController extends \yii\rest\ActiveController
{

    use \api\components\traits\ControllersCommonTrait ;

    /**
     * @var string
     */
    public $serializer = 'common\components\CustomSerializer' ;

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['delete']);
        return $actions;
    }
}
