<?php

use yii\helpers\Html;
use yii\helpers\Url;


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

$resultData = [
	[
		'advertiser' => 'Libris',
		'cost' => 1000,
		'aprobate' => 1500,
		'asteptare' => 200,
		'anulate' => 500,
		'total' => 2200,
	],
	[
		'advertiser' => 'Epiesa',
		'cost' => 3000,
		'aprobate' => 3500,
		'asteptare' => 200,
		'anulate' => 500,
		'total' => 4200,
	],
	[
		'advertiser' => 'Carturesti',
		'cost' => 4000,
		'aprobate' => 4500,
		'asteptare' => 200,
		'anulate' => 500,
		'total' => 5200,
	],
];

$dataProvider = new \yii\data\ArrayDataProvider([
    'key'=>'id',
    'allModels' => $resultData,
    'sort' => [
        //'attributes' => ['id', 'name', 'email'],
    ],
]);

$columns = [
	[
		'attribute' => 'advertiser',
		'vAlign' => 'middle',
		'width' => '150px',
        'headerOptions' => [
            'rowspan' => '2',
        ],
	],
	[
		'attribute' => 'cost',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '10%',
        'headerOptions' => [
            'rowspan' => '2',
        ],
	],
	[
		'attribute' => 'aprobate',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '10%',
        'header' => 'VALOARE COMISIOANE',
        'headerOptions' => [
        	'style' => 'text-align: center;',
            'colspan' => '4',
        ],
    ],
	[
		'attribute' => 'asteptare',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '10%',
        'headerOptions' => [
            'style' => 'display: none;',
        ],
    ],
	[
		'attribute' => 'anulate',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '10%',
        'headerOptions' => [
            'style' => 'display: none;',
        ],
    ],
	[
		'attribute' => 'total',
		'hAlign' => 'right',
		'vAlign' => 'middle',
		'width' => '10%',
		'header' => 'a<br>b',
		'format' => 'raw',
        'headerOptions' => [
            'style' => 'display: none;',
        ],
    ],
];

// Generate a bootstrap responsive striped table with row highlighted on hover
echo GridView::widget([
	'id' => 'global-report',
	'dataProvider'=> $dataProvider,
	'columns' => $columns,
	
	'resizableColumns'=>false,
	'resizeStorageKey'=>Yii::$app->user->id . '-' . date("m"),
	
	'responsive'=>true,
	'hover'=>true,
	
    'floatHeader'=>false,
    'floatHeaderOptions'=>['scrollingTop'=>'50'],
    'showPageSummary' => false,

    'panel' => [
        'type' => GridView::TYPE_ACTIVE,
        'heading' => '<i class="fa fa-dollar"></i>  Global Report',
        '_before' => '<div style="padding-top: 7px;"><em>* Resize table columns just like a spreadsheet by dragging the column edges.</em></div>',
    ],
    'toolbar' => [],
	
]);
?>
