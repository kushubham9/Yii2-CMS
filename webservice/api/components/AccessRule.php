<?php
/**
 * Created by PhpStorm.
 * @author Ihor Karas <ihor@karas.in.ua>
 */

namespace api\components;

/**
 * Class AccessRule
 * @package api\components
 *
 * @inheritdoc
 */
class AccessRule extends \yii\filters\AccessRule
{

	/**
	 * @var array
     */
	public $scopes=[];
}