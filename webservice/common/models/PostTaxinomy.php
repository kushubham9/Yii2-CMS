<?php

namespace common\models;

use \common\models\base\PostTaxinomy as BasePostTaxinomy;

/**
 * This is the model class for table "post_taxinomy".
 */
class PostTaxinomy extends BasePostTaxinomy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_replace_recursive(parent::rules(),
	    [
            [['taxinomy_id', 'post_id'], 'required'],
            [['taxinomy_id', 'post_id'], 'integer']
        ]);
    }
	
}
