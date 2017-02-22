<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 15/01/17
 * Time: 3:15 PM
 */

namespace frontend\models;

use common\models\Constants;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use common\models\Post;
use yii\helpers\Url;
use common\models\Category;
use backend\models\Images;

class Posts extends Post
{
    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['id','title','user_id','updated_at','cat.id'],'safe']
        ];
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),
            [
                'cat.id'
            ]);
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function featured($params)
    {
        $query = self::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS]);

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5,
            ],
            'sort' =>
            [
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'title' => SORT_ASC,
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     *
     * Default method to search for posts.
     */
    public function search($params){
        $query = self::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS]);
//        $query->joinWith('postTaxinomies taxinomy');
        // add conditions that should always apply here

        if (isset($params['category'])){
            if (!is_array($params['category']))
                $params['category'] = (array)$params['category'];
            $query->joinWith('postCategories category');
            foreach ($params['category'] as $cat_slug){
                $category = Category::find()->where(['slug'=>$cat_slug])->one();
                if ($category)
                    $query->andFilterWhere(['category.category_id'=>$category->id]);
                else{
                    throw new \Exception("Invalid Search Query.");
                }
            }
        }

        if (isset($params['q'])){
            $query->andFilterWhere(['like', 'lower(post.title)', $params['q']]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 15,
            ],
            'sort' =>
                [
                    'defaultOrder' => [
                        'created_at' => SORT_DESC,
                        'title' => SORT_ASC,
                    ]
                ]
        ]);

        return $dataProvider;
    }

    /**
     * @param $post self
     */

    public static function getPostInformation($post, $options = false)
    {
        $data = [];
        //Get The First Category.
        $data['categories'] = $post->categories;
        if ($data['categories']){
            $data['primeCategory'] = $data['categories'][0];
        }
        if ($data['primeCategory']){
            $data['categoryUrl'] = Url::to(['/news/search', 'category'=>$data['primeCategory']->slug]);
        }
        else{
            $data['categoryUrl'] = "";
        }
        // Get the author of the post.
        $data['author'] = $post->user ? $post->user : null;

        // Author Name.
        $data['authorName'] = ($data['author']->usermeta)?$data['author']->usermeta->first_name.' '.$data['author']->usermeta->last_name : $data['author']->username;

        //Get the URL of the image.
//        $data['imageUrl'] = \Yii::$app->imagemanager->getImagePath($post->featured_image);

//        $url = Url::to(['http://backend.cms.build/site/post-image', 'imageId'=>$post->featured_image], true);
//        $url = 'http://backend.cms.build/site/post-image?'.
//                        'imageId='.$post->featured_image.
//                        '&imageWidth=300&imageHeight=300';

        $data['imageUrl'] = (new Posts())->getImagePath($post->featured_image, $options);
        $data['postTitle'] = $post->title;
        $data['postUrl'] = Url::to(['/post/'.$post->slug]);
        $data['postDate'] = \Yii::$app->formatter->asDate($post->created_at,'long');
        $data['authorUrl'] = Url::to(['/user/'.$post->user->username]);
        $data['postContent'] = substr(strip_tags($post->content), 0, 125)."...";

        return $data;
    }

    /**
     * @param $image integer
     * @param $options array
     */
    public function getImagePath($imageId, $options){

        $imageWidth = isset($options['imageWidth'])
                        ? $options['imageWidth']
                        : ((isset($options['thumb']) && $options['thumb']) ? Constants::THUMB_WIDTH : Constants::MAX_IMAGE_WIDTH);

        $imageHeight = isset($options['imageHeight'])
                        ? $options['imageHeight']
                        : ((isset($options['thumb']) && $options['thumb']) ? Constants::THUMB_HEIGHT : Constants::MAX_IMAGE_HEIGHT);

        $url = Constants::BACKEND_ADDRESS.
                '/site/post-image'.
                '?imageId='.$imageId.
                '&imageHeight='.$imageHeight.
                '&imageWidth='.$imageWidth;
        return $this->httpGet($url);
    }

    private function httpGet($url)
    {
//        die ($url);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

        $output=curl_exec($ch);

        curl_close($ch);
        return $output;
//        die ($output);
    }
}