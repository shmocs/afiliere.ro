<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\icons\Icon;
Icon::map($this, Icon::BSG);

use yii\helpers\VarDumper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Cost;
use app\models\Import;
use yii\helpers\ArrayHelper;

?>

				
<?php


$columns = [
	[
		'attribute' => 'id',
		'hAlign' => 'center',
		'vAlign' => 'middle',
		'width' => '4%',
	],
	[
		'attribute' => 'campaign_date',
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
		'attribute' => 'campaign_name',
		'vAlign' => 'middle',
		'width' => '170px',
	],
	[
		'attribute' => 'advertiser',
		'vAlign' => 'middle',
		'width' => '150px',
	],
	[
		'attribute' => 'clicks',
		'hAlign' => 'center',
		'vAlign' => 'middle',
		'width' => '10%',
	],

	[
		'attribute' => 'cost',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '5%',
		'pageSummary' => true
	],
	[
		'attribute' => 'created_at',
		'hAlign' => 'center',
		'vAlign' => 'middle',
		'width' => '10%',
		'value' => function ($model, $key, $index, $widget) {
			if (!empty($model->modified_at)) {
				$date = '<a data-toggle="tooltip" data-placement="left" data-html="true" title="Created at: '.$model->created_at.' Modified at: '.$model->modified_at.'"><i class="glyphicon glyphicon-edit"></i></a> '.$model->created_at.'';
			} else {
				$date = $model->created_at;
			}
			return $date;
		},
		'format' => 'raw',
	],
	[
		'attribute' => 'import_id',
		'vAlign' => 'middle',
		'value' => function ($model, $key, $index, $widget) {
			$a_dld = Html::a(
				Icon::show('download', ['class'=>'fa-1x'], Icon::BSG),
				'#',
				['data-href' => '/jQueryFileUpload/server/php/files/'.$model->import->filename, 'class' => 'download', 'title' => 'Download the file', 'target' => '_blank']);
			$a_filter = Html::a($model->import->filename,
				'index?Sale[import_id]='.$model->import_id,
				['title' => 'Filtrare fisier', 'onclick' => 'alert("Filtrare dupa fisier!")']);
			
			
			return '<div style="overflow-x: auto; width: 100%; max-width: 300px; white-space: nowrap;">'.$a_dld.$a_filter.'</div>';
		},
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(Import::find()->where(['type' => 'cost'])->orderBy('created_at DESC')->asArray()->all(), 'id', 'filename'),
		'filterWidgetOptions' => [
			'pluginOptions' => ['allowClear' => true],
		],
		'filterInputOptions' => ['placeholder' => 'all'],
		'format' => 'raw',
	],
];

// Generate a bootstrap responsive striped table with row highlighted on hover
echo GridView::widget([
	'dataProvider'=> $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $columns,
	
	'resizableColumns'=>false,
	'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
	
	'responsive'=>true,
	'hover'=>true,
	
	'pjax'=>true,
	'pjaxSettings'=>[
		'neverTimeout'=>true,
		'beforeGrid'=>'',
		'afterGrid'=>'',
	],
    'floatHeader'=>false,
    'floatHeaderOptions'=>['scrollingTop'=>'50'],
    'showPageSummary' => false,
    'toolbar' => [
        [
            'content'=>
                Html::button('<i class="glyphicon glyphicon-plus"></i>', [
                    'id'=>'add_costs',
                    'type'=>'button',
                    'title'=> 'Import Costs CSV',
                    'class'=>'btn btn-success',
	                'data-toggle' => 'modal',
	                'data-target' => '#modal-import',
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'id' => 'reload-grid',
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid',
                ]),
        ],
        '{toggleData}'
    ],

	
    'panel' => [
        'type' => GridView::TYPE_ACTIVE,
        'heading' => '<i class="fa fa-money"></i> Costs imported from AdWords',
        '_before' => '<div style="padding-top: 7px;"><em>* Resize table columns just like a spreadsheet by dragging the column edges.</em></div>',
    ],
	
]);
?>

<?= $this->render('_partial/import.php') ?>