<?php


require Yii::getAlias('@app') . '/assets/ReportsAsset.php';
app\assets\ReportsAsset::register($this);

$this->registerJs('$(\'.sidebar-toggle\').click();');

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\daterange\DateRangePicker;

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
    //'allModels' => $resultData,
    'allModels' => $dataProvider,
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
						<h3 class="box-title pull-left"><i class="fa fa-th-list"></i> Global report</h3>
						
						<div class="pull-left col-md-2">
							<select name="date_type" id="date_type">
								<option value="click_date" <?php if ($date_type == 'click_date') echo 'selected="selected"';?>>Click Date</option>
								<option value="conversion_date" <?php if ($date_type == 'conversion_date') echo 'selected="selected"';?>>Conversion Date</option>
							</select>
						</div>
						
						<div class="pull-right col-md-3">
							
								<div class="drp-container col-md-10">
									<?php
									echo DateRangePicker::widget([
										'name'=>'date_range',
										'value' => $date_range,
										'presetDropdown'=>true,
										'hideInput'=>true,
										
										'pluginOptions' => [
											'locale' => [
												'cancelLabel' => 'Clear',
												'format' => 'YYYY-MM-DD',
											]
										],
									
									]);
									?>
								</div>
								<div class="col-md-2">
									
									<a href="#" class="btn btn-primary filter_date_range">Submit</a>
								</div>

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
								<th width="50">ateptare</th>
								<th width="50">anulate</th>
								<th width="50">total</th>
							</tr>
							</thead>
							
							<tbody>
							<?php
							foreach ($dataProvider->models as $row) {
								//continue;
								
								/*
					            [cost] => 239.81
					            [advertiser] => Apiland.ro
					            [valoare_comisioane_aprobate] => 408.04
					            [valoare_comisioane_asteptare] => 101.04
					            [valoare_comisioane_anulate] => 33.04
					            [valoare_comisioane_total] => 542.12
					            [volum_comisioane_aprobate] => 13
					            [volum_comisioane_asteptare] => 5
					            [volum_comisioane_anulate] => 3
					            [volum_comisioane_total] => 21
					            [value_accepted_details] => 2018-04|673.44,2018-07|139.08,2018-03|581.04,2018-06|400.52,2018-05|915.31
					            [volume_accepted_details] => 2018-04|30,2018-07|4,2018-03|22,2018-06|17,2018-05|38
					            [value_rejected_details] => 2018-04|0.00,2018-07|11.80,2018-03|20.16,2018-06|21.24,2018-05|0.00
					            [volume_rejected_details] => 2018-04|0,2018-07|2,2018-03|1,2018-06|1,2018-05|0
					            [valoare_comisioane_aprobate_avg] => 541.878000
					            [volum_comisioane_aprobate_avg] => 22.2000
					            [valoare_comisioane_anulate_avg] => 10.640000
					            [volum_comisioane_anulate_avg] => 0.8000
								 * */
								//VarDumper::dump($row, 10, true);continue;
								
								$ram_valoare_tooltip = '';
								if (isset($row['value_accepted_details'])) {
									$ram_valoare_tooltip .= $row['value_accepted_details'];
									$ram_valoare_tooltip .= '<br>'.$row['value_rejected_details'];
								}
								
								?>
								<tr>
									<td><?=$row['advertiser'];?></td>
									<td align="right"><?=$row['profit_garantat'];?></td>
									<td align="right"><?=$row['profit_estimat'];?></td>
									<td align="right"><?=$row['ra_valoare'];?>%</td>
									<td align="right"><?=$row['ram_valoare'];?>%</td>
									<td align="right"><?=$row['ra_volum'];?>%</td>
									<td align="right"><?=$row['ram_volum'];?>%</td>
									<td align="right"><?=$row['cost'];?></td>
									<td align="right"><?=$row['valoare_comisioane_aprobate'];?></td>
									<td align="right"><?=$row['valoare_comisioane_asteptare'];?></td>
									<td align="right"><?=$row['valoare_comisioane_anulate'];?></td>
									<td align="right"><?=$row['valoare_comisioane_total'];?></td>
									<td align="right"><?=$row['volum_comisioane_aprobate'];?></td>
									<td align="right"><?=$row['volum_comisioane_asteptare'];?></td>
									<td align="right"><?=$row['volum_comisioane_anulate'];?></td>
									<td align="right"><?=$row['volum_comisioane_total'];?></td>
								</tr>
								<?php
							}
							?>
							</tbody>
							
							<tfoot>
							<tr>
								<th></th>
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


