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

//VarDumper::dump($_SERVER, 10, true);


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


$fullExportMenu = ExportMenu::widget([
	'dataProvider' => $dataProvider,
	'columns' => $searchModel->getExportColumns(),
	'target' => ExportMenu::TARGET_SELF,
	'fontAwesome' => true,
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

// Generate a bootstrap responsive striped table with row highlighted on hover
echo GridView::widget([
	'dataProvider'=> $dataProvider,
	'filterModel' => $searchModel,
	'columns' => $searchModel->getGridColumns(),
	
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
        //'{export}',
	    $fullExportMenu,
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



<div class="content-wrapper2 hide">
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

