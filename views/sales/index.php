<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\GridView;
use yii\helpers\VarDumper;
use kartik\grid\GridView;
use app\models\Sale;
use yii\helpers\ArrayHelper;

//$this->registerJs('/js/sales.js'); => <script type="text/javascript">jQuery(function ($) {/js/sales.js});</script>

//VarDumper::dump($_SERVER, 10, true);

$gridColumns = [
//	[
//	    'class' => 'kartik\grid\SerialColumn',
//	    'width' => '25px',
//	],
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
	    'width' => '100px',
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
	    'width' => '10%',
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
			return '<div style="overflow-x: scroll; width: 100%; max-width: 300px; white-space: nowrap;">'.$model->referrer.'</div>';
		},
		'format' => 'raw',
	],
//	[
//	    'attribute' => 'import_file_id',
//	    'vAlign' => 'middle',
//		'value' => function ($model, $key, $index, $widget) {
//			return Html::a($model->import_file_id,
//				'#',
//				['title' => 'Filtrare fisier', 'onclick' => 'alert("Filtrare dupa fisier!")']);
//		},
//		'format' => 'raw',
//	],
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
];

?>

<!-- Main content -->
<!-- <section class="content">-->
<!--	<div class="row">-->
<!--		<div class="col-xs-12">-->
<!--			-->
<!--			<div class="box">-->
<!--				<div class="box-header">-->
<!--					<h3 class="box-title">Data Table With Full Features</h3>-->
<!--				</div>-->
<!--				<!-- /.box-header -->
<!--				<div class="box-body">-->
				
<?php

// Generate a bootstrap responsive striped table with row highlighted on hover
echo GridView::widget([
	'dataProvider'=> $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $gridColumns,
	
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
                    'type'=>'button',
                    'title'=> 'Add Book',
                    'class'=>'btn btn-success'
                ]) . ' '.
                Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['index'], [
                    'class' => 'btn btn-default',
                    'title' => 'Reset Grid',
                ]),
        ],
        '{export}',
        '{toggleData}'
    ],

	
    'panel' => [
        'type' => GridView::TYPE_ACTIVE,
        'heading' => '<i class="fa fa-dollar"></i>  Sales',
        '_before' => '<div style="padding-top: 7px;"><em>* Resize table columns just like a spreadsheet by dragging the column edges.</em></div>',
    ],
	
	'export' => [
		'header' => '',
        'fontAwesome' => true,
	],
 
	'exportConfig' => [
		GridView::CSV => ['label' => 'Save as CSV', 'icon' => 'file-excel-o'],
	],

]);
?>
<!--				</div>-->
<!--			</div>-->
<!--		</div>-->
<!--	</div>-->
<!--</section>-->



<div class="content-wrapper2">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Data Tables
			<small>advanced tables</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">Tables</a></li>
			<li class="active">Data tables</li>
		</ol>
	</section>
	
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">Data Table With Full Features</h3>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="example1" class="table table-bordered table-striped table-hover">
							<thead>
							<tr>
								<th style="width: 20px;">ID</th>
								<th style="width: 50px;">Platforma</th>
								<th style="width: 200px;">Advertiser</th>
								<th style="width: 120px;">Data Click</th>
								<th style="width: 120px;">Data Conversie</th>
								<th style="width: 60px; text-align: right;">Comision</th>
								<th>Refferer</th>
								<th style="width: 100px;">Status</th>
							</tr>
							</thead>
							
							<tbody>
								<?php
								foreach ($dataProvider->models as $row) {
									?>
									<tr>
										<td><?=$row->id;?></td>
										<td><?=$row->platform;?></td>
										<td><?=$row->advertiser;?></td>
										<td><?=$row->click_date;?></td>
										<td><?=$row->conversion_date;?></td>
										<td style="text-align: right;"><?=$row->amount;?></td>
										<td><div style="overflow-x: scroll; width: 600px; white-space: nowrap;"><?=$row->referrer;?></div></td>
										<td style="text-align: center;"><?=$row->status;?></td>
									</tr>
									<?php
								}
								?>
							</tbody>
							
							<tfoot>
							<tr>
								<th>Advertiser</th>
								<th>Data Click</th>
								<th>Data Conversie</th>
								<th>Comisionului</th>
								<th>Refferer</th>
								<th>Status</th>
							</tr>
							</tfoot>
						</table>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>

