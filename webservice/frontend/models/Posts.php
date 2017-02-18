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

    public function featuredCat($catID)
    {
        $query = self::find()->where(['type'=>Constants::TYPE_POST, 'status'=>Constants::DEFAULT_POST_STATUS]);
        $query->joinWith('postCategories cat');
        $query->andWhere(['category_id'=>$catID]);

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

        return $dataProvider;
    }
}