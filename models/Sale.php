<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;



/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property string $platform
 * @property string $advertiser
 * @property string $click_date
 * @property string $conversion_date
 * @property string $amount
 * @property string $referrer
 * @property string $status
 * @property string $created_at
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platform', 'advertiser', 'click_date', 'conversion_date', 'amount', 'referrer', 'status', 'created_at'], 'required'],
            [['click_date', 'conversion_date', 'created_at'], 'safe'],
            [['amount'], 'number'],
            [['platform', 'advertiser', 'referrer', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platform' => 'Platform',
            'advertiser' => 'Advertiser',
            'click_date' => 'Click Date',
            'conversion_date' => 'Conversion Date',
            'amount' => 'Amount',
            'referrer' => 'Referrer',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
	
	
	public function search($params) {
		
		$query = self::find();
		
		$dataProvider = new ActiveDataProvider(
			[
				'query' => $query,
				'pagination' => [
					'pageSize' => 10,
				],
			]
		);
		
		if (!($this->load($params))) {
			return $dataProvider;
		}
		
		// 'platform', 'advertiser', 'click_date', 'conversion_date', 'amount', 'referrer', 'status', 'created_at'
		
		$query->andFilterWhere(['id' => $this->id]);
		$query->andFilterWhere(['platform' => $this->channel]);
		$query->andFilterWhere(['advertiser' => $this->idcountry]);
		$query->andFilterWhere(['click_date' => $this->user_id]);
		$query->andFilterWhere(['conversion_date' => $this->priority]);
		$query->andFilterWhere(['amount', $this->from]);
		$query->andFilterWhere(['like', 'referrer', $this->from]);
		$query->andFilterWhere(['status', $this->from]);
		$query->andFilterWhere(['created_at', $this->from]);
		
		return $dataProvider;
	}
	
}