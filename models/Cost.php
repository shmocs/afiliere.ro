<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\VarDumper;

use kartik\grid\GridView;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cost".
 *
 * @property int $id
 * @property string $campaign_date
 * @property string $campaign_name
 * @property string $advertiser
 * @property string $clicks
 * @property string $cost
 * @property string $created_at
 * @property string $modified_at
 * @property string $import_id
 */
class Cost extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'cost';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['campaign_date', 'campaign_name', 'advertiser', 'clicks', 'cost', 'created_at'], 'required'],
            [['id', 'campaign_date', 'created_at', 'modified_at', 'import_id'], 'safe'],
            [['clicks', 'cost'], 'number'],
            [['campaign_name', 'advertiser'], 'string', 'max' => 255],
	        [['campaign_date'], 'match', 'pattern' => '/^.+\s\-\s.+$/'],
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
            'campaign_date' => 'Day',
            'advertiser' => 'Advertiser',
            'clicks' => 'Clicks',
            'cost' => 'Cost',
            'referrer' => 'Referrer',
            'created_at' => 'Created At',
            'import_id' => 'Import File',
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
		$query->andFilterWhere(['like', 'advertiser', $this->advertiser]);
		$query->andFilterWhere(['cost' => $this->cost]);
		$query->andFilterWhere(['created_at' => $this->created_at]);
		$query->andFilterWhere(['import_id' => $this->import_id]);
		
		
		if( isset($this->campaign_date) && $this->campaign_date != '') {
			$date_explode = explode(" - ",$this->campaign_date);
			$date1 = trim($date_explode[0]);
			$date2 = trim($date_explode[1]);
			$query->andFilterWhere(['between', 'campaign_date', $date1.' 00:00:00', $date2.' 23:59:59']);
		}
		
		//VarDumper::dump($query->createCommand()->getRawSql(), 10, true);
		
		return $dataProvider;
	}
	
	
	public static function getDataChart01() {
		$sql = "
			SELECT `campaign_date`, `advertiser`, SUM(`clicks`) AS `total_clicks`, SUM(`cost`) AS `total_cost`
			FROM `sale` WHERE 1
			GROUP BY `campaign_date`, `advertiser`
			ORDER BY `date` ASC
    	";
		$costs = Yii::$app->db->createCommand($sql)->queryAll();
		
		
		$data = $record = [];
		$keep_date = '';
		
		foreach ($costs as $cost) {
			if ($keep_date != $cost['campaign_date']) {
				
				if (!empty($keep_date)) {
					$data[] = $record[$keep_date];
				}
				
				$keep_date = $cost['campaign_date'];
				$record = [
					$keep_date => [
						'campaign_date' => $keep_date,
						'total_sales' => 0,
						'total_conversions' => 0
					]
				];
				
			}
			
			$record[$keep_date][$cost['advertiser'].'_sales_amount'] = $cost['sum'];
			$record[$keep_date][$cost['advertiser'].'_sales_nr'] = $cost['cnt'];
			$record[$keep_date]['total_sales'] += $cost['sum'];
			$record[$keep_date]['total_conversions'] += $cost['cnt'];
		}
		$data[] = $record[$keep_date];
		
		return $data;
	}
	
}
