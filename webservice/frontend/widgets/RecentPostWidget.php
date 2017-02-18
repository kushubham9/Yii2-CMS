<?php

namespace frontend\widgets;
use yii\base\Widget;
use common\models\Post;
use common\models\Constants;

/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 18/02/17
 * Time: 10:25 PM
 */
class RecentPostWidget extends Widget
{
    private $post_model;
    public $count = 5;
    public $displayThumb = true;
    public $containerClass = "";

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->post_model = Post::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS])->orderBy('created_at DESC, title')->limit($this->count)->all();
    }

    public function run()
    {
        echo $this->render('recent_post',[
            'post_model'=>$this->post_model,
            'containerClass' => $this->containerClass
        ]);
    }
}