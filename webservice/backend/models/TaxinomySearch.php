<?php

namespace backend\models;

use common\models\Constants;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Taxinomy;

/**
 * backend\models\TaxinomySearch represents the model behind the search form about `common\models\Taxinomy`.
 */
 class TaxinomySearch extends Taxinomy
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['type', 'value', 'created_at', 'updated_at', 'description', 'slug'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Taxinomy::find()->where(['type'=>Constants::TAXINOMY_TYPE_TAGS]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'attributes' => [
                    'value',
                    'created_at',
                ],
                'defaultOrder' => [
                    'created_at' => SORT_DESC,
                    'value' => SORT_ASC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'value', $this->value])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
