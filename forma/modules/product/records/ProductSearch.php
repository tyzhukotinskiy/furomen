<?php

namespace forma\modules\product\records;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

/**
 * ProductSearch represents the model behind the search form about `\forma\modules\product\records\Product`.
 */
class ProductSearch extends Product
{
    public $color_name;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'category_id', 'manufacturer_id', 'year_chart', 'batcher', 'country_id', 'volume'], 'integer'],
            [['customs_code', 'sku', 'name', 'note', 'type', 'color_name'], 'safe'],
            [['proof', 'rating'], 'number'],
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
        $query = Product::find()
            ->joinWith('color', false, 'LEFT JOIN')
            ->joinWith(['accessory'])
            ->andWhere(['accessory.user_id' => Yii::$app->getUser()->getIdentity()->getId()])
            ->andWhere(['accessory.entity_class' => Product::className()]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $dataProvider->setSort([
            'attributes' => ArrayHelper::merge($dataProvider->sort->attributes, [
                'color_name' => [
                    'asc' => ['color.name' => SORT_ASC],
                    'desc' => ['color.name' => SORT_DESC],
                ],
            ]),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'product.id' => $this->id,
            'product.category_id' => $this->category_id,
            'product.type_id' => $this->type_id,
            'product.manufacturer_id' => $this->manufacturer_id,
            'product.volume' => $this->volume,
            'product.year_chart' => $this->year_chart,
            'product.proof' => $this->proof,
            'product.rating' => $this->rating,
            'product.batcher' => $this->batcher,
            'product.country_id' => $this->country_id,
            'product.color_id' => $this->color_id,
            'color.name' => $this->color_name,
        ]);

        $query->andFilterWhere(['like', 'product.sku', $this->sku])
            ->andFilterWhere(['like', 'product.customs_code', $this->customs_code])
            ->andFilterWhere(['like', 'product.name', $this->name])
            ->andFilterWhere(['like', 'product.note', $this->note]);

        return $dataProvider;
    }
}
