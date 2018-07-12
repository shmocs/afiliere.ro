<?php

use yii\helpers\Html;
use yii\helpers\Url;

use kartik\icons\Icon;
Icon::map($this, Icon::BSG);

use yii\helpers\VarDumper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Sale;
use app\models\Import;
use yii\helpers\ArrayHelper;

//$this->registerJs('/js/sales.js'); => <script type="text/javascript">jQuery(function ($) {/js/sales.js});</script>
$this->registerJs('$(\'.sidebar-toggle\').click();');

//VarDumper::dump($_SERVER, 10, true);
//VarDumper::dump(\Yii::getAlias('@webroot'), 10, true);

?>

				
<?php

$filename_date = date("Y-m-d_H-i-s");
if (isset($_GET['Sale']['conversion_date'])) {
	$filename_date = $_GET['Sale']['conversion_date'];
}

$fullExportMenu = ExportMenu::widget([
	'dataProvider' => $dataProvider,
	'columns' => $searchModel->getExportColumns(),
	'target' => ExportMenu::TARGET_SELF,
	'fontAwesome' => true,
	'filename' => 'SalesAdwordsExport_'.$filename_date,
	'showConfirmAlert' => true,
	'showColumnSelector' => true,
	'dropdownOptions' => [
		'label' => 'Export',
		'class' => 'btn btn-default',
	],
	
	'exportConfig' => [
		ExportMenu::FORMAT_CSV => [
			'label' => 'Save as CSV',
			'icon' => 'file-excel-o',
			'alertMsg' => 'The CSV export file will be generated for download.',
		],
		ExportMenu::FORMAT_TEXT => false,
		//ExportMenu::FORMAT_PDF => false,
		ExportMenu::FORMAT_HTML => false,
		ExportMenu::FORMAT_EXCEL => false,
		ExportMenu::FORMAT_EXCEL_X => false,
	],
]);

$columns = [
	[
		'attribute' => 'id',
		'hAlign' => 'center',
		'vAlign' => 'middle',
		'width' => '4%',
	],
	[
		'attribute' => 'platform_id',
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
		'width' => '15%',
	],
	[
		'attribute' => 'status',
		'hAlign' => 'center',
		'vAlign' => 'middle',
		'width' => '7%',
		'value' => function ($model, $key, $index, $widget) {
			if ($model->status == 'accepted') {
				$bg = 'green';
			}
			if ($model->status == 'pending') {
				$bg = 'yellow';
			}
			if ($model->status == 'rejected') {
				$bg = 'red';
			}
			return "<span class='label bg-{$bg}'>&nbsp;</span> <code>" . $model->status . '</code>';
		},
		'format' => 'raw',
		'filterType' => GridView::FILTER_SELECT2,
		'filter' => ArrayHelper::map(Sale::find()->select('status')->distinct()->asArray()->all(), 'status', 'status'),
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
		'filter' => ArrayHelper::map(Import::find()->where(['type' => 'sale'])->orderBy('created_at DESC')->asArray()->all(), 'id', 'filename'),
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
                    'id'=>'add_sales',
                    'type'=>'button',
                    'title'=> 'Import Sales CSV',
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
        //'{export}',
	    $fullExportMenu,
        '{toggleData}'
    ],

	
    'panel' => [
        'type' => GridView::TYPE_ACTIVE,
        'heading' => '<i class="fa fa-dollar"></i>  Sales',
        '_before' => '<div style="padding-top: 7px;"><em>* Resize table columns just like a spreadsheet by dragging the column edges.</em></div>',
    ],
	
	// ---> these valid only for {export} template that is commented
	'export' => [
		'header' => 'kk',
        'fontAwesome' => true,
	],
 	'exportConfig' => [
		GridView::CSV => ['label' => 'Save as CSV', 'icon' => 'file-excel-o'],
	],
	// <---
]);
?>

<?= $this->render('_partial/import.php') ?>