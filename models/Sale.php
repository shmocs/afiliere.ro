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

	public function getGridColumns() {
		
		$columns = [
			[
				'attribute' => 'id',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '4%',
			],
			[
				'attribute' => 'platform',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '7%',
				'value' => function ($model, $key, $index, $widget) {
					return Html::a($model->platform,
						'#',
						['title' => 'Filtrare dupa platforma', 'onclick' => 'alert("Filtrare dupa platforma!")']);
				},
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map(Sale::find()->orderBy('platform')->asArray()->all(), 'platform', 'platform'),
				'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'any'],
				'format' => 'raw'
			],
			[
				'attribute' => 'advertiser',
				'vAlign' => 'middle',
				'width' => '150px',
			],
			[
				'attribute' => 'click_date',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '10%',
			],
			[
				'attribute' => 'conversion_date',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '240px',
				'filterType' => GridView::FILTER_DATE_RANGE,
				'filterWidgetOptions' => [
					'presetDropdown'=>true,
					'pluginOptions' => [
						'opens'=>'right',
						'locale' => [
							'cancelLabel' => 'Clear',
							'format' => 'YYYY-MM-DD',
						]
					],
				],
			
			],
			[
				'attribute' => 'amount',
				'hAlign' => 'right',
				'vAlign' => 'middle',
				'width' => '5%',
				'pageSummary' => true
			],
			[
				'attribute' => 'referrer',
				'vAlign' => 'middle',
				'value' => function ($model, $key, $index, $widget) {
					return '<div style="overflow-x: scroll; width: 100%; max-width: 250px; white-space: nowrap;">'.$model->referrer.'</div>';
				},
				'format' => 'raw',
			],
			[
				'attribute' => 'status',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '7%',
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map(Sale::find()->orderBy('status')->asArray()->all(), 'status', 'status'),
				'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'any'],
			],
			[
				'attribute' => 'created_at',
				'hAlign' => 'center',
				'vAlign' => 'middle',
				'width' => '10%',
			],
			[
				'attribute' => 'import_id',
				'vAlign' => 'middle',
				'value' => function ($model, $key, $index, $widget) {
					$a = Html::a($model->import->filename,
						'index?Sale[import_id]='.$model->import_id,
						['title' => 'Filtrare fisier', 'onclick' => 'alert("Filtrare dupa fisier!")']);
					return '<div style="overflow-x: auto; width: 100%; max-width: 300px; white-space: nowrap;">'.$a.'</div>';
				},
				'filterType' => GridView::FILTER_SELECT2,
				'filter' => ArrayHelper::map(Import::find()->orderBy('created_at DESC')->asArray()->all(), 'id', 'filename'),
				'filterWidgetOptions' => [
					'pluginOptions' => ['allowClear' => true],
				],
				'filterInputOptions' => ['placeholder' => 'all'],
				'format' => 'raw',
			],
		];
		
		return $columns;
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
					'pageSize' => 2,
				],
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
	
}
