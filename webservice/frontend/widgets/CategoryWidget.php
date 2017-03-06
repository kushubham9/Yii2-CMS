<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 06/03/17
 * Time: 11:24 PM
 */

namespace frontend\widgets;
use backend\models\Post;
use yii\base\Widget;
use common\models\Category;
use common\models\Constants;
use yii\helpers\Url;

class CategoryWidget extends Widget
{
    public $count;
    private $category = [];

    public function init(){
        parent::init();
        $cats = Category::find()->all();
        foreach ($cats as $cat){
            $count = $this->getPost_count($cat->id);
            $url = Url::to(['/news/search','type'=>'category', 'q'=>$cat->slug]);
            array_push($this->category,['name'=>$cat->name, 'url'=>$url, 'count'=>$count]);
        }
    }

    public function run()
    {
        return $this->render('category_widget.twig',[
            'category' => $this->category
        ]);
    }

    private function getPost_count($category){
        return Post::find()->joinWith('postCategories cat')->where(['type'=>Constants::TYPE_POST, 'status'=> Constants::DEFAULT_POST_STATUS, 'cat.category_id'=>$category])->count();
    }
}