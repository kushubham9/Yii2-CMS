<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 15/01/17
 * Time: 3:15 PM
 */

namespace backend\models;
use common\models\Constants;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class PostSearch extends Post
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
    public function search($params)
    {
        $query = self::find()->where(['type'=>Constants::TYPE_POST]);
        $query->select([
                'post.*',
            ])
            ->joinWith('categories cat')
            ->orderBy('post.id DESC');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>
            [
                'defaultOrder' => 'created_at DESC'
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'post.id' => $this->id,
            'post.updated_at' => $this->updated_at,
            'cat.id' => $this->getAttribute('cat.id'),
            'post.status' => $this->status,
            'post.user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'post.title', $this->title]);

        return $dataProvider;
    }
}