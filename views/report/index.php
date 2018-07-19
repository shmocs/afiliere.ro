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
                            
                            $totals = [
                            	'profit_garantat' => 0,
                            	'profit_estimat' => 0,
                            	'cost' => 0,
                            	'valoare_comisioane_aprobate' => 0,
                            	'valoare_comisioane_asteptare' => 0,
                            	'valoare_comisioane_anulate' => 0,
                            	'valoare_comisioane_total' => 0,
                            	'volum_comisioane_aprobate' => 0,
                            	'volum_comisioane_asteptare' => 0,
                            	'volum_comisioane_anulate' => 0,
                            	'volum_comisioane_total' => 0,
                            ];
							
							foreach ($dataProvider->models as $row) {
								//continue;
								
								//nu ma intereseaza sa apara acolo daca nu i-am promovat in perioada aia
                                if ($row['cost'] == 0 || $row['volum_comisioane_total'] == 0) continue;
		
								//VarDumper::dump($row, 10, true);continue;
								
								$ram_valoare_tooltip = '';
								if (isset($row['value_accepted_details'])) {
									$ram_valoare_tooltip .= $row['value_accepted_details'];
									$ram_valoare_tooltip .= '<br>'.$row['value_rejected_details'];
								}
								
								$ram_volum_tooltip = '';
								if (isset($row['volume_accepted_details'])) {
                                    $ram_volum_tooltip .= $row['volume_accepted_details'];
                                    $ram_volum_tooltip .= '<br>'.$row['volume_rejected_details'];
								}
								
								$totals['profit_garantat'] += $row['profit_garantat'];
								$totals['profit_estimat'] += $row['profit_estimat'];
								$totals['cost'] += $row['cost'];
								
								$totals['valoare_comisioane_aprobate'] += $row['valoare_comisioane_aprobate'];
								$totals['valoare_comisioane_asteptare'] += $row['valoare_comisioane_asteptare'];
								$totals['valoare_comisioane_anulate'] += $row['valoare_comisioane_anulate'];
								$totals['valoare_comisioane_total'] += $row['valoare_comisioane_total'];
								
								$totals['volum_comisioane_aprobate'] += $row['volum_comisioane_aprobate'];
								$totals['volum_comisioane_asteptare'] += $row['volum_comisioane_asteptare'];
								$totals['volum_comisioane_anulate'] += $row['volum_comisioane_anulate'];
								$totals['volum_comisioane_total'] += $row['volum_comisioane_total'];
								
								?>
								<tr>
									<td><a target="_blank" href="/report/advertiser?date_range=<?=$date_range;?>&date_type=<?=$date_type;?>&advertiser=<?=$row['advertiser'];?>"><?=$row['advertiser'];?></a></td>
									<td align="right"><?=$row['profit_garantat'];?></td>
									<td align="right"<?php if ($row['profit_estimat'] < 0) echo ' style="background-color: #f8d7da;"';?>><?=$row['profit_estimat'];?></td>
									<td align="right"><?=$row['ra_valoare'];?>%</td>
									<td align="right" title="<?=$ram_valoare_tooltip;?>" <?php if ($row['ram_valoare'] < 75) echo ' style="background-color: #f8d7da;"';?>><?=$row['ram_valoare'];?>%</td>
									<td align="right"><?=$row['ra_volum'];?>%</td>
									<td align="right" title="<?=$ram_volum_tooltip;?>"><?=$row['ram_volum'];?>%</td>
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
								<th style="text-align: right;">Totaluri:</th>
								<th style="text-align: right;"><?=$totals['profit_garantat'];?></th>
								<th style="text-align: right;"><?=$totals['profit_estimat'];?></th>
								<th></th>
								<th></th>
								<th></th>
								<th></th>
								<th style="text-align: right;"><?=$totals['cost'];?></th>
								<th style="text-align: right;"><?=$totals['valoare_comisioane_aprobate'];?></th>
								<th style="text-align: right;"><?=$totals['valoare_comisioane_asteptare'];?></th>
								<th style="text-align: right;"><?=$totals['valoare_comisioane_anulate'];?></th>
								<th style="text-align: right;"><?=$totals['valoare_comisioane_total'];?></th>
								<th style="text-align: right;"><?=$totals['volum_comisioane_aprobate'];?></th>
								<th style="text-align: right;"><?=$totals['volum_comisioane_asteptare'];?></th>
								<th style="text-align: right;"><?=$totals['volum_comisioane_anulate'];?></th>
								<th style="text-align: right;"><?=$totals['volum_comisioane_total'];?></th>
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


