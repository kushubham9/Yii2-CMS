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
use common\models\Base\User as BaseUser2;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

class UserSearch extends BaseUser2
{
    public $fullName;

    public function rules()
    {
        return [
            [['id','status'],'integer'],
            [['id','email','created_at','updated_at','statusName', 'username','um.first_name','um.last_name','fullName'],'safe']
        ];
    }

    public function scenario()
    {
        return Model::scenarios();
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
//        var_dump($this->getAttributes());
//        die();
        $query = self::find();
        $query->joinWith(['usermeta um'])
            ->select([
                'user.*',
                'um.first_name',
                'um.last_name'
            ]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' =>
                    [
                        'created_at' => [
                            'asc' => ['user.created_at' => SORT_ASC, 'user.updated_at' => SORT_ASC],
                            'desc' => ['user.created_at' => SORT_DESC, 'user.updated_at' => SORT_DESC],
                        ],
                        'fullName' => [
                            'asc' => ['um.first_name' => SORT_ASC, 'um.last_name' => SORT_ASC],
                            'desc' => ['um.first_name' => SORT_DESC, 'um.last_name' => SORT_DESC],
                        ]
                    ],

                'defaultOrder' =>
                    [
                        'created_at' => SORT_DESC,
                        'fullName' => SORT_ASC
                    ]
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
            'user.id' => $this->id,
            'user.created_at' => $this->created_at,
            'user.updated_at' => $this->updated_at,
            'user.status' => $this->status,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'um.first_name', $this->fullName])
            ->orFilterWhere(['like', 'um.last_name', $this->fullName]);

        return $dataProvider;
    }

}