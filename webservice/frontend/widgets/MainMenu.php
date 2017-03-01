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
        \Yii::$app->cache->flush();
        $key = "MENU_LINKS";
        $this->link = \Yii::$app->cache->getOrSet($key,function(){
            return array_merge($this->defaultItems(), $this->loadCategories());
        });
    }

    private function defaultItems(){
        return
            ['home_custom_link' =>
                [
                    'parent' =>
                        [
                            'title' =>  'Home',
                            'href'  =>  Url::to(['/'])
                        ],
                ],

            'news_custom_link' =>
                [
                    'parent' =>
                    [
                        'title' =>  'News',
                        'href'  =>  Url::to(['/news'])
                    ],
                ]
            ];
    }

    private function loadCategories(){
        $category_key = "MENU_CATEGORY";
        $categoriesLink = \Yii::$app->cache->getOrSet($category_key, function () {
            $links = [];
            $categories = Category::find()->where(['parent_category'=>null])->orderBy('name')->all();
            foreach ($categories as $category){
                $links[$category->slug] = [
                    'parent' => ['title' => $category->name, 'href' => Url::to(['/news/search','type'=>'category','q'=>$category->slug])]
                ];
                $childCategory = $category->categories;
                if ($childCategory){
                    foreach ($childCategory as $cCategory){
                        $links[$category->slug]['child'][] = ['title' => $cCategory->name, 'href' => Url::to(['/news/search','category'=>$cCategory->slug])];
                    }
                }
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