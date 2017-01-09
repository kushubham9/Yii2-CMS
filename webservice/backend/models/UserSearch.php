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
    public $statusName;
    public $firstName;
    public $lastName;

    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['id','email','created_at','updated_at','firstName','lastName','statusName', 'username'],'safe']
        ];
    }
    public function scenario()
    {
        return Model::scenarios();
    }

//    public function attributes()
//    {
//        $attributes =  ArrayHelper::merge(parent::attributes(),
//            [
//                'firstName',
//                'lastName',
//                'statusName'
//            ]);
//        return $attributes;
//    }

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
                'um.first_name as firstName',
                'um.last_name as lastName',
                'st.name as statusName',
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'um.first_name', $this->firstName]);

        return $dataProvider;
    }

}