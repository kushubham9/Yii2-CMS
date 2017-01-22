<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 08/01/17
 * Time: 4:20 PM
 */

namespace backend\models;

use yii\base\Model;
use backend\models\User as BaseUser;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserSearch extends BaseUser
{
    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['id','email','created_at','updated_at','statusName', 'username','um.first_name'],'safe']
        ];
    }
    public function scenario()
    {
        return Model::scenarios();
    }

    public function attributes()
    {
        return ArrayHelper::merge(parent::attributes(),
                    ['um.first_name',
                    'um.last_name',
                    'st.name']);
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
        $query = self::find();
        $query->joinWith(['usermeta um'])->joinWith(['status0 st'])
            ->select([
                'user.*',
                'um.first_name',
                'um.last_name',
                'st.name',
            ]);

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

        // grid filtering conditions
        $query->andFilterWhere([
            'user.id' => $this->id,
            'user.created_at' => $this->created_at,
            'user.updated_at' => $this->updated_at,
            'user.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'um.username', $this->username])
            ->andFilterWhere(['like', 'um.email', $this->email])
            ->andFilterWhere(['like', 'um.first_name', $this->getAttributes(['um.first_name'])]);

        return $dataProvider;
    }

}