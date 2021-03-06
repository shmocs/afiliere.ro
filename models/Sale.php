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
 * @property string $platform_id
 * @property string $advertiser
 * @property string $click_date
 * @property string $conversion_date
 * @property string $amount
 * @property string $referrer
 * @property string $original_status
 * @property string $status
 * @property string $created_at
 * @property string $modified_at
 * @property string $import_id
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
            [['platform', 'platform_id', 'advertiser', 'click_date', 'conversion_date', 'amount', 'referrer', 'status', 'created_at'], 'required'],
            [['id', 'click_date', 'conversion_date', 'created_at', 'import_id'], 'safe'],
            [['amount'], 'number'],
            [['platform', 'advertiser', 'referrer', 'original_status', 'status'], 'string', 'max' => 255],
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
            'platform_id' => 'Platform ID',
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
                'header' => 'Conversion Time',
				'value' => function ($model, $key, $index, $widget) {
					$utc_date = gmdate('Y-m-d H:i:s', strtotime($model->conversion_date)) . '';
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
		$query->andFilterWhere(['like', 'platform_id', $this->platform_id]);
		$query->andFilterWhere(['like', 'advertiser', $this->advertiser]);
		$query->andFilterWhere(['click_date' => $this->click_date]);
		//$query->andFilterWhere(['conversion_date' => $this->conversion_date]);
		$query->andFilterWhere(['amount' => $this->amount]);
		$query->andFilterWhere(['like', 'referrer', $this->referrer]);
		$query->andFilterWhere(['status' => $this->status]);
		$query->andFilterWhere(['created_at' => $this->created_at]);
		$query->andFilterWhere(['import_id' => $this->import_id]);
		
		
		if( isset($this->conversion_date) && $this->conversion_date != '') {
			$date_explode = explode(" - ",$this->conversion_date);
			$date1 = trim($date_explode[0]);
			$date2 = trim($date_explode[1]);
			$query->andFilterWhere(['between', 'conversion_date', $date1.' 00:00:00', $date2.' 23:59:59']);
		}
		
		//VarDumper::dump($query->createCommand()->getRawSql(), 10, true);
		
		return $dataProvider;
	}
	
	
	public static function getDataChart01($chartdiv_profit_interval, $start_date, $end_date) {
        
        $date_sales = "DATE(`conversion_date`)";
        if ($chartdiv_profit_interval == 7) {
            $date_sales = "DATE_FORMAT(`conversion_date`, '%x/%v')";
        }
        if ($chartdiv_profit_interval == 31) {
            $date_sales = "DATE_FORMAT(`conversion_date`, '%Y-%m')";
        }
        
        
        $sql = "
			SELECT {$date_sales} AS `date`, `platform`, SUM(`amount`) AS `sum`, SUM(1) AS `cnt`
			FROM `sale`
			WHERE 1
				AND `conversion_date` BETWEEN '{$start_date}' AND '{$end_date}'
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
                
                $date_label = '';
                $pairs = explode('/', $sale['date']);
                if (isset($pairs[1])) {
                    $year = $pairs[0];
                    $week = $pairs[1];
                    
                    $dto = new \DateTime();
                    $ret['week_start'] = $dto->setISODate($year, $week)->format('Y-m-d');
                    $ret['week_end'] = $dto->modify('+6 days')->format('Y-m-d');
                    $date_label = ' ('.join($ret, ':').')';
                }
				
				$keep_date = $sale['date'];
				$record = [
					$keep_date => [
						'date' => $keep_date . $date_label,
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
	
}
