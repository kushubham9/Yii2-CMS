<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/02/17
 * Time: 9:41 PM
 */

namespace frontend\widgets;

use yii\base\Widget;
use common\models\Category;
use yii\helpers\Url;

class MainMenu extends Widget
{
    public $link = [];
    public function init(){
        parent::init();
        $key = "MENU_LINKS1";
        $this->link = \Yii::$app->cache->getOrSet($key,function(){
            return array_merge($this->defaultItems(), $this->loadCategories());
        });
    }

    private function defaultItems(){
        return [
            'Home' => Url::to(['/']),
            'News' => Url::to(['/news'])
        ];
    }

    private function loadCategories(){
        $category_key = "MENU_CATEGORY";
        $categoriesLink = \Yii::$app->cache->getOrSet($category_key, function () {
            $links = [];
            $categories = Category::find()->orderBy('name')->all();
            foreach ($categories as $category){
                $links[$category->name] = Url::to(['/news','category'=>$category->slug]);
            }
            return $links;
        });
        return $categoriesLink;
    }

    public function run()
    {
        echo $this->render('menu_items',[
            'links' => $this->link
        ]);
    }
}