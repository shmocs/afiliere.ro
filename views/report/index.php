<?php


require Yii::getAlias('@app') . '/assets/ReportsAsset.php';
app\assets\ReportsAsset::register($this);

$this->registerJs('$(\'.sidebar-toggle\').click();');

use yii\helpers\Html;
use yii\helpers\Url;


use yii\helpers\VarDumper;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use app\models\Sale;
use app\models\Import;
use yii\helpers\ArrayHelper;

//VarDumper::dump($_SERVER, 10, true);
//VarDumper::dump(\Yii::getAlias('@webroot'), 10, true);
//VarDumper::dump($dataProvider->models, 10, true);

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
    //'key'=>'id',
    'allModels' => $resultData,
    'sort' => [
        //'attributes' => ['id', 'name', 'email'],
    ],
]);
?>


<div class="content-wrapper2">

	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title"><i class="fa fa-th-list"></i> Global report</h3>
						<div class="pull-right">
							Search control
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body">
						<table id="global_report_table" class="table table-bordered table-striped table-hover">
							<thead>
							<tr>
								<th rowspan="2">ADVERTISER</th>
								<th colspan="2" style="text-align: center;">PROFIT</th>
								<th colspan="2" style="text-align: center;">RATA APROBARE (valoare)</th>
								<th colspan="2" style="text-align: center;">RATA APROBARE (volum)</th>
								<th width="50" style="vertical-align: middle; text-align: center;" rowspan="2">COST</th>
								<th colspan="4" style="text-align: center;">VALOARE COMISIOANE</th>
								<th colspan="4" style="text-align: center;">VOLUM COMISIOANE</th>
							</tr>
							<tr>
								<th width="50">garantat</th>
								<th width="50">estimat</th>
								<th width="50">curenta</th>
								<th width="50">medie</th>
								<th width="50">curenta</th>
								<th width="50">medie</th>
								<th width="50">aprobate</th>
								<th width="50">asteptare</th>
								<th width="50">anulate</th>
								<th width="50">total</th>
								<th width="50">aprobate</th>
								<th width="50">șteptare</th>
								<th width="50">anulate</th>
								<th width="50">total</th>
							</tr>
							</thead>
							
							<tbody>
							<?php
							foreach ($dataProvider->models as $row) {
								//VarDumper::dump($row, 10, true);continue;
								?>
								<tr>
									<td><?=$row['advertiser'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['aprobate'];?></td>
									<td align="right"><?=$row['asteptare'];?></td>
									<td align="right"><?=$row['anulate'];?></td>
									<td align="right"><?=$row['total'];?></td>
								</tr>
								<?php
							}
							?>
							</tbody>
							
							<tfoot>
							<tr>
								<th>Advertiser</th>
								<th>Cost</th>
								<th>Accepted</th>
								<th>Pending</th>
								<th>Rejected</th>
								<th>Total</th>
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


