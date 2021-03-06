<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 12:06 PM
 */

namespace frontend\widgets;
use yii\base\Widget;
use common\models\Comment;

class CommentFormWidget extends Widget
{
    public $postId;
    private $comment_model;
    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->comment_model = new Comment();
    }

    public function run()
    {
        echo $this->render('comment_form',[
            'comment_model' => $this->comment_model,
            'postId' => $this->postId
        ]);
    }
}