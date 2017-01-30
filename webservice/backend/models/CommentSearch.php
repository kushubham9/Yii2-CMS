<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/01/17
 * Time: 8:31 PM
 */

namespace backend\models;
use common\models\Comment;
use yii\data\ActiveDataProvider;

class CommentSearch extends Comment
{
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['author_name','author_email','content','user_id','post_id','parent_comment','created_at','updated_at'],'safe']
        ];
    }

    public function search($params)
    {
        $query = self::find();

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id
        ]);

//        $query->andFilterWhere(['like', 'name', $this->name])
//            ->andFilterWhere(['like', 'description', $this->description])
//            ->orderBy('created_at desc');

        return $dataProvider;
    }
}