<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

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
            [['id', 'click_date', 'conversion_date', 'created_at', 'import_id'], 'safe'],
            [['amount'], 'number'],
            [['platform', 'advertiser', 'referrer', 'status'], 'string', 'max' => 255],
	        [['conversion_date'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
        ];
    }
	
	public function getImport() {
		return $this->hasOne(
			\app\models\Import::className(),
			['id' => 'import_id']
		);
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
            'import_id' => 'Import File',
        ];
    }

	
	public function getExportColumns() {
		$columns = [
			[
				'attribute' => 'conversion_date',
				'value' => function ($model, $key, $index, $widget) {
					$utc_date = gmdate('Y-m-d H:i:s', strtotime($model->conversion_date)) . ' UTC';
					return $utc_date;
				},
			],
			[
				'attribute' => 'amount',
				'header' => 'Conversion Value',
			],
			[
				'attribute' => 'referrer',
				'value' => function ($model, $key, $index, $widget) {
					return preg_replace('/.*gclid=(.*)$/', '$1', $model->referrer);
				},
				'header' => 'Google Click Id',
			],
			[
				'attribute' => 'id',
				'value' => function ($model, $key, $index, $widget) {
					return 'comisioane';
				},
				'header' => 'Conversion Name',
			],
		];
		
		return $columns;
	}
	
	
	public function search($params) {
		
		$query = self::find();
		
		$dataProvider = new ActiveDataProvider(
			[
				'query' => $query,
				'pagination' => [
					'pageSize' => 10,
				],
				'sort' => [
					'defaultOrder' => [
						'id' => SORT_DESC,
					]
				]
			]
		);
		
		if (!($this->load($params))) {
			return $dataProvider;
		}
		
		// 'platform', 'advertiser', 'click_date', 'conversion_date', 'amount', 'referrer', 'status', 'created_at'
		
		$query->andFilterWhere(['id' => $this->id]);
		$query->andFilterWhere(['like', 'platform', $this->platform]);
		$query->andFilterWhere(['like', 'advertiser', $this->advertiser]);
		$query->andFilterWhere(['click_date' => $this->click_date]);
		//$query->andFilterWhere(['conversion_date' => $this->conversion_date]);
		$query->andFilterWhere(['amount' => $this->amount]);
		$query->andFilterWhere(['like', 'referrer', $this->referrer]);
		$query->andFilterWhere(['status' => $this->status]);
		$query->andFilterWhere(['created_at' => $this->created_at]);
		
		
		if( isset($this->conversion_date) && $this->conversion_date != '') {
			$date_explode = explode(" - ",$this->conversion_date);
			$date1 = trim($date_explode[0]);
			$date2 = trim($date_explode[1]);
			$query->andFilterWhere(['between', 'conversion_date', $date1, $date2]);
		}
		
		//VarDumper::dump($query->createCommand()->getRawSql(), 10, true);
		
		return $dataProvider;
	}
	
	
	public static function getAllForCharts() {}
	
	public static function getDataChart01() {
		$sql = "
			SELECT DATE(`conversion_date`) AS `date`, `platform`, SUM(`amount`) AS `sum`, SUM(1) AS `cnt`
			FROM `sale` WHERE 1
			GROUP BY `date`, `platform`
			ORDER BY `date` ASC
    	";
		$sales = Yii::$app->db->createCommand($sql)->queryAll();
		
		
		$data = $record = [];
		$keep_date = '';
		
		foreach ($sales as $sale) {
			if ($keep_date != $sale['date']) {
				
				if (!empty($keep_date)) {
					$data[] = $record[$keep_date];
				}
				
				$keep_date = $sale['date'];
				$record = [
					$keep_date => [
						'date' => $keep_date,
						'total_sales' => 0,
						'total_conversions' => 0
					]
				];
				
			}
			
			$record[$keep_date][$sale['platform'].'_sales_amount'] = $sale['sum'];
			$record[$keep_date][$sale['platform'].'_sales_nr'] = $sale['cnt'];
			$record[$keep_date]['total_sales'] += $sale['sum'];
			$record[$keep_date]['total_conversions'] += $sale['cnt'];
		}
		$data[] = $record[$keep_date];
		
		return $data;
	}
	
	public static function _getDataChart01() {
		
		ini_set('memory_limit', '256M');
    	
    	$data = $record = [];
		$keep_date = '';
		rsort($sales);
		
		foreach ($sales as $sale) {
			if ($keep_date != $sale['conversion_date']) {
				
				if (!empty($keep_date)) {
					unset($record[$keep_date]);
					$data[] = $record;
				}
				
				$keep_date = $sale['conversion_date'];
				$record = [
					$keep_date => [
						'date' => $keep_date,
						'total' => 0
					]
				];

			}
			if (!isset($record[$keep_date][$sale['platform']])) {
				$record[$keep_date][$sale['platform']] = 0;
			}
			
			$record[$keep_date][$sale['platform']] += $sale['amount'];
			$record[$keep_date]['total'] += $sale['amount'];
    	}
		$data[] = $record;
		
    	return json_encode($data);
	}
	
}
