<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 11:32 AM
 */

namespace frontend\widgets;
use yii\base\Widget;
use common\models\Category;
use common\models\Post;
use common\models\Constants;

class FeaturedCategory extends Widget
{
    public $type = 1;
    public $category;
    public $count;
    private $defaultTitle;
    public $title = '';
    public $post_model = '';

    public function init()
    {
        parent::init(); // TODO: Change the autogenerated stub
        $this->category = $this->category ? $this->category  :  unserialize(\Yii::$app->params['settings']['sticky_widget_'.$this->type.'_category']);
        $this->count = $this->count ? $this->count  :(int)\Yii::$app->params['settings']['sticky_widget_'.$this->type.'_count'];
        $this->defaultTitle = $this->defaultTitle ? $this->defaultTitle : \Yii::$app->params['settings']['sticky_widget_'.$this->type.'_default_title'];

//        if (is_array($this->category))
        if (is_array($this->category)  && sizeof($this->category)> 0)
        {
            if (!$this->title){
                $categories = Category::find()->where(['id'=>$this->category])->all();
                $title = [];
                foreach ($categories as $category)
                {
                    $title[] = $category->name;
                }
                $this->title = implode(' & ', $title);
            }

            if (!$this->post_model){
                $query = Post::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS]);
                $query->joinWith('postCategories cat');
                $query->andWhere(['category_id'=>$this->category]);

                $this->post_model = $query->orderBy('created_at DESC')->limit($this->count)->all();
            }
        }

        else{
            $query = Post::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS]);
            $this->post_model = $query->orderBy('created_at DESC')->limit($this->count)->all();

            if (!$this->title)
                $this->title = $this->defaultTitle;
        }
    }

    public function run(){
        echo $this->render('main_featuredCat'.$this->type,[
            'post_model' => $this->post_model,
            'post_count' => $this->count,
            'title' => $this->title
        ]);
    }

}