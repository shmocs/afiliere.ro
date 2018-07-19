<?php


require Yii::getAlias('@app') . '/assets/ReportsAsset.php';
app\assets\ReportsAsset::register($this);


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

$profits_json = json_encode($profits_data);
$ROAS_json = json_encode($ROAS_data);

?>
<!-- Styles -->
<style>
	#chartdiv_profit, #chartdiv_roas {
		width: 100%;
		height: 400px;
	}
	
	#chartdiv_roas {
		overflow-y: auto;
	}
</style>
<!-- Resources -->
<script src="https://www.amcharts.com/lib/3/amcharts.js"></script>
<script src="https://www.amcharts.com/lib/3/serial.js"></script>
<script src="https://www.amcharts.com/lib/3/amstock.js"></script>
<script src="https://www.amcharts.com/lib/3/plugins/export/export.min.js"></script>
<link rel="stylesheet" href="https://www.amcharts.com/lib/3/plugins/export/export.css" type="text/css" media="all" />
<script src="https://www.amcharts.com/lib/3/themes/light.js"></script>
<!--<script src="http://www.amcharts.com/lib/3/plugins/dataloader/dataloader.min.js" type="text/javascript"></script>-->

<!-- Chart code -->
<script>

	var chart_profit = AmCharts.makeChart("chartdiv_profit", {
		"type": "serial",
		"theme": "light",

		"precision": 2,
		"valueAxes": [{
			"id": "v1",
			"title": "LEI",
			"position": "left",
			"autoGridCount": false,
			"labelFunction": function(value) {
				return "" + Math.round(value, 2) + "";
			},
		}],
		"graphs": [{
			"id": "g1",
			"valueAxis": "v1",
			"lineColor": "#e1ede9",
			"fillColors": "#e1ede9",
			"fillAlphas": 1,
			"type": "column",
			"title": "Sales",
			"valueField": "sales",
			"clustered": false,
			"columnWidth": 0.7,
			"legendValueText": "[[value]]",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}, {

			"id": "g2",
			"valueAxis": "v1",
			"lineColor": "#62cf73",
			"fillColors": "#62cf73",
			"fillAlphas": 1,
			"type": "column",
			"title": "Costs",
			"valueField": "costs",
			"clustered": false,
			"columnWidth": 0.4,
			"legendValueText": "[[value]]",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}, {


			"id": "g3",
			"valueAxis": "v1",
			"bullet": "round",
			"bulletBorderAlpha": 1,
			"bulletColor": "#FFFFFF",
			"bulletSize": 5,
			"hideBulletsCount": 50,
			"lineThickness": 2,
			"lineColor": "#20acd4",
			//"type": "smoothedLine",
			"title": "Profit",
			"useLineColorForBulletBorder": true,
			"valueField": "profit",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}],
		"chartScrollbar": {
			"graph": "g1",
			"oppositeAxis": false,
			"offset": 30,
			"scrollbarHeight": 50,
			"backgroundAlpha": 0,
			"selectedBackgroundAlpha": 0.1,
			"selectedBackgroundColor": "#888888",
			"graphFillAlpha": 0,
			"graphLineAlpha": 0.5,
			"selectedGraphFillAlpha": 0,
			"selectedGraphLineAlpha": 1,
			"autoGridCount": true,
			"color": "#AAAAAA"
		},
		"chartCursor": {
			"pan": true,
			"valueLineEnabled": true,
			"valueLineBalloonEnabled": true,
			"cursorAlpha": 0.1,
			"valueLineAlpha": 0.5
		},
		"categoryField": "date",
		"categoryAxis": {
			//"parseDates": true,
			//"dashLength": 1,
			//"minorGridEnabled": true
			"labelRotation": 45,
			"labelFunction": function(valueText, serialDataItem, categoryAxis) {
				//var pairs = valueText.split('/');
				//var raw_date = new Date(pairs[0], 0, 1+((pairs[1]-1)*7));
				//return raw_date.getDate() + '-' + (raw_date.getMonth() + 1);
				//return "|" + valueText + "|";
				
				return valueText.replace(/(.*)\s.*/, '$1');
			},
		},
		"legend": {
			"useGraphSettings": true,
			"position": "top"
		},
		"balloon": {
			"borderThickness": 1,
			"shadowAlpha": 0
		},
		"export": {
			"enabled": true
		},
		"dataProvider": <?php echo $profits_json;?>
	});

	var chart_roas = AmCharts.makeChart("chartdiv_roas", {
		"type": "serial",
		"theme": "light",

		"precision": 2,
		"valueAxes": [{
			"id": "v1",
			"title": "LEI",
			"position": "left",
			"autoGridCount": false,
			"labelFunction": function(value) {
				return "" + Math.round(value, 2) + "";
			},
		}],
		"graphs": [{

			"id": "g1",
			"valueAxis": "v1",
			"bullet": "round",
			"bulletBorderAlpha": 1,
			"bulletColor": "#FFFFFF",
			"bulletSize": 5,
			"hideBulletsCount": 50,
			"lineThickness": 2,
			"lineColor": "#20acd4",
			//"type": "smoothedLine",
			"title": "CPC",
			"useLineColorForBulletBorder": true,
			"valueField": "cpc",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}, {


			"id": "g2",
			"valueAxis": "v1",
			"bullet": "round",
			"bulletBorderAlpha": 1,
			"bulletColor": "#FFFFFF",
			"bulletSize": 5,
			"hideBulletsCount": 50,
			"lineThickness": 2,
			"lineColor": "#ff851b",
			//"type": "smoothedLine",
			"title": "ROAS",
			"useLineColorForBulletBorder": true,
			"valueField": "roas",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}],
		"chartScrollbar": {
			"graph": "g1",
			"oppositeAxis": false,
			"offset": 30,
			"scrollbarHeight": 50,
			"backgroundAlpha": 0,
			"selectedBackgroundAlpha": 0.1,
			"selectedBackgroundColor": "#888888",
			"graphFillAlpha": 0,
			"graphLineAlpha": 0.5,
			"selectedGraphFillAlpha": 0,
			"selectedGraphLineAlpha": 1,
			"autoGridCount": true,
			"color": "#AAAAAA"
		},
		"chartCursor": {
			"pan": true,
			"valueLineEnabled": true,
			"valueLineBalloonEnabled": true,
			"cursorAlpha": 0.1,
			"valueLineAlpha": 0.5
		},
		"categoryField": "date",
		"categoryAxis": {
			//"parseDates": true,
			//"dashLength": 1,
			//"minorGridEnabled": true
			"labelRotation": 45,
			"labelFunction": function(valueText, serialDataItem, categoryAxis) {
				return valueText.replace(/(.*)\s.*/, '$1');
			},
		},
		"legend": {
			"useGraphSettings": true,
			"position": "top"
		},
		"balloon": {
			"borderThickness": 1,
			"shadowAlpha": 0
		},
		"export": {
			"enabled": true
		},
		"dataProvider": <?php echo $ROAS_json;?>
	});
</script>

<?php

$_advertiser_data = [
	'clicks' => 4529,
	'conversions' => 527,
	'commission_amount' => 1738.00,
	'cost' => 2147.33,
	'time_lag' => '',
	'rma_volume' => 87,
	'rma_value' => 90,
];


if ($advertiser_data['conversion_rate'] >= 10) {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-up';
    $advertiser_data['conversion_rate_type'] = 'green';
} else if ($advertiser_data['conversion_rate'] >= 3) {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-up';
    $advertiser_data['conversion_rate_type'] = 'orange';
} else {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-down';
    $advertiser_data['conversion_rate_type'] = 'red';
}

if ($advertiser_data['profit'] > 0) {
    $advertiser_data['profit_thumbs'] = 'fa-thumbs-o-up';
    $advertiser_data['profit_type'] = 'green';
} else {
    $advertiser_data['profit_thumbs'] = 'fa-thumbs-o-down';
    $advertiser_data['profit_type'] = 'red';
}

if ($advertiser_data['cost'] > 0) {
	if ($advertiser_data['roas'] > 1) {
        $advertiser_data['roas_type'] = 'green';
	} else {
        $advertiser_data['roas_type'] = 'orange';
	}
} else {
	$advertiser_data['roas'] = 0;
    $advertiser_data['roas_type'] = 'aqua';
}


if ($advertiser_data['rma_volume'] >= 90) {
	$advertiser_data['rma_volume_thumbs'] = 'fa-thumbs-o-up';
	$advertiser_data['rma_volume_type'] = 'green';
} else if ($advertiser_data['rma_volume'] >= 80) {
	$advertiser_data['rma_volume_thumbs'] = 'fa-thumbs-o-up';
	$advertiser_data['rma_volume_type'] = 'orange';
} else {
	$advertiser_data['rma_volume_thumbs'] = 'fa-thumbs-o-down';
	$advertiser_data['rma_volume_type'] = 'red';
}

if ($advertiser_data['rma_value'] >= 90) {
	$advertiser_data['rma_value_thumbs'] = 'fa-thumbs-o-up';
	$advertiser_data['rma_value_type'] = 'green';
} else if ($advertiser_data['rma_value'] >= 80) {
	$advertiser_data['rma_value_thumbs'] = 'fa-thumbs-o-up';
	$advertiser_data['rma_value_type'] = 'orange';
} else {
	$advertiser_data['rma_value_thumbs'] = 'fa-thumbs-o-down';
	$advertiser_data['rma_value_type'] = 'red';
}
?>


<div class="content-wrapper2">
	
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				
				<div class="box">
					<div class="box-header">
						<h3 class="box-title pull-left"><i class="fa fa-th-list"></i> Advertiser report</h3>
						
						<div class="pull-left col-md-2">
							Advertiser:
							<?php
							echo Html::activeDropDownList(
								new Sale(),
								'advertiser',
								ArrayHelper::map($advertisers, 'advertiser', 'advertiser'),
								['value' => $advertiser]
							)
							?>
						</div>
						<div class="pull-left col-md-2">
							Date type:
							<select name="date_type" id="date_type">
								<option value="click_date" <?php if ($date_type == 'click_date') echo 'selected="selected"';?>>Click Date</option>
								<option value="conversion_date" <?php if ($date_type == 'conversion_date') echo 'selected="selected"';?>>Conversion Date</option>
							</select>
						</div>
						<div class="pull-left col-md-2">
							Commission:
							<select name="commission_type" id="commission_type">
								<option value="accepted" <?php if ($commission_type == 'accepted') echo 'selected="selected"';?>>Accepted</option>
								<option value="accepted_pending" <?php if ($commission_type == 'accepted_pending') echo 'selected="selected"';?>>Accepted + Pending</option>
							</select>
						</div>
						
						<div class="pull-right col-md-4 col-sx-12">
							
							<div class="drp-container col-md-9 col-xs-12">
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
							<div class="col-md-3 col-xs-12">
								
								<a href="#" class="btn btn-primary filter_date_range">Submit</a>
							</div>
						
						</div>
					</div>
					<!-- /.box-header -->
					<div class="box-body hidden">
						
					
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
			
				
			<div class="callout callout-success hidden">
				<h4>I am a success callout!</h4>
				
				<p>This is a green callout.</p>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-hand-pointer-o"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Clicks</span>
						<span class="info-box-number"><?=$advertiser_data['clicks'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-check-square"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Conversions</span>
						<span class="info-box-number"><?=$advertiser_data['conversions'];?></span>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box bg-<?=$advertiser_data['conversion_rate_type'];?>">
					<span class="info-box-icon"><i class="fa <?=$advertiser_data['conversion_rate_thumbs'];?>"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Conversion Rate</span>
						<span class="info-box-number"><?=$advertiser_data['conversion_rate'];?>%</span>
						
						<div class="progress">
							<div class="progress-bar" style="width: <?=$advertiser_data['conversion_rate'];?>%"></div>
						</div>
						<span class="progress-description"><?=$advertiser_data['clicks'];?> / <?=$advertiser_data['conversions'];?></span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Commission</span>
						<span class="info-box-number"><?=$advertiser_data['commission_amount'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Cost</span>
						<span class="info-box-number"><?=$advertiser_data['cost'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-<?=$advertiser_data['profit_type'];?>"><i class="fa fa-dollar"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Profit</span>
						<span class="info-box-number"><?=$advertiser_data['profit'];?></span>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="small-box bg-green">
					<div class="inner">
						<h3><?=$advertiser_data['revenue_click'];?></h3>
						
						<p>Revenue / Click</p>
					</div>
					<div class="iconn">
						<i class="fa fa-money"></i> / <i class="fa fa-mouse-pointer"></i>
					</div>
					<a href="#" class="small-box-footer">&nbsp;</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-money"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Revenue/Click</span>
						<span class="info-box-number"><?=$advertiser_data['revenue_click'];?></span>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="small-box bg-orange">
					<div class="inner">
						<h3><?=$advertiser_data['cpc'];?></h3>
						
						<p>Cost / Click</p>
					</div>
					<div class="iconn">
						<i class="fa fa-credit-card"></i> / <i class="fa fa-mouse-pointer"></i>
					</div>
					<a class="small-box-footer">CPC</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">CPC</span>
						<span class="info-box-number"><?=$advertiser_data['cpc'];?></span>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="small-box bg-<?=$advertiser_data['profit_type'];?>">
					<div class="inner">
						<h3><?=$advertiser_data['profit_click'];?></h3>
						
						<p>Profit / Click</p>
					</div>
					<div class="iconn">
						<i class="fa fa-dollar"></i> / <i class="fa fa-mouse-pointer"></i>
					</div>
					<a class="small-box-footer">&nbsp;</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Profit/Click</span>
						<span class="info-box-number"><?=$advertiser_data['profit_click'];?></span>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="small-box bg-<?=$advertiser_data['roas_type'];?>">
					<div class="inner">
						<h3><?=$advertiser_data['roas'];?></h3>
						
						<p>Commission / Cost</p>
					</div>
					<div class="iconn">
						<i class="fa fa-money"></i> / <i class="fa fa-credit-card"></i>
					</div>
					<a class="small-box-footer">ROAS</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-<?=$advertiser_data['roas_type'];?>"><i class="fa fa-star"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">ROAS</span>
						<span class="info-box-number"><?=$advertiser_data['roas'];?></span>
					</div>
				</div>
			</div>
			
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="info-box small-box bg-<?=$advertiser_data['rma_volume_type'];?>">
					
					<div class="inner">
						<h3><?=$advertiser_data['rma_volume'];?>%</h3>
						
						<div style="height: 18px;">&nbsp;</div>
						
						<div class="progress">
							<div class="progress-bar" style="width: <?=$advertiser_data['rma_volume'];?>%"></div>
						</div>
					</div>
					<div class="iconn">
						<i class="fa <?=$advertiser_data['rma_value_thumbs'];?>"></i>
					</div>
					
					<a class="small-box-footer">RMA Volume</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">RMA Volume</span>
						<span class="info-box-number"><?=$advertiser_data['rma_volume'];?></span>
					</div>
				</div>
			</div>
			
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<!-- small box -->
				<div class="info-box small-box bg-<?=$advertiser_data['rma_value_type'];?>">
					
					<div class="inner">
						<h3><?=$advertiser_data['rma_value'];?>%</h3>
						
						<div style="height: 18px;">&nbsp;</div>
						
						<div class="progress">
							<div class="progress-bar" style="width: <?=$advertiser_data['rma_value'];?>%"></div>
						</div>
					</div>
					<div class="iconn">
						<i class="fa <?=$advertiser_data['rma_value_thumbs'];?>"></i>
					</div>
					
					<a class="small-box-footer">RMA Value</a>
				</div>
			</div>
			<div class="col-md-2 col-sm-6 col-xs-12 hidden">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">RMA Value</span>
						<span class="info-box-number"><?=$advertiser_data['rma_value'];?></span>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-safari"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Time Lag</span>
						<span class="info-box-number"><?=$advertiser_data['time_lag'];?></span>
					</div>
				</div>
			</div>
	
			
			<img class="hidden" src="/images/demo.png" width="1024" alt="">
			
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
	
	
	<section class="content">
		<div class="row">
			<div class="col-md-6">
				
				<!-- LINE CHART -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title pull-left">Sales / Costs / Profit</h3>
						
						<div class="col-md-6 col-sx-12">
							
							<div class="btn-group pull-right">
								<button type="button" class="btn btn-xs btn-default chartdiv_profit_interval<?php if ($chartdiv_profit_interval == 1) echo ' active';?>">1</button>
								<button type="button" class="btn btn-xs btn-default chartdiv_profit_interval<?php if ($chartdiv_profit_interval == 7) echo ' active';?>">7</button>
								<button type="button" class="btn btn-xs btn-default chartdiv_profit_interval<?php if ($chartdiv_profit_interval == 31) echo ' active';?>">31</button>
								<input type="hidden" name="chartdiv_profit_interval" id="chartdiv_profit_interval" value="<?=$chartdiv_profit_interval;?>" />
							</div>
						</div>
						
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body chart-responsive">
						<div id="chartdiv_profit"></div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			
			</div>
			
			<div class="col-md-6">
				
				<!-- LINE CHART -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">ROAS / CPC</h3>
						
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body chart-responsive">
						<div id="chartdiv_roas">
							<?php
							//echo '<pre>';print_r($ROAS_data);echo '</pre>';
							?>
						</div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			
			</div>
		</div>
	</section>
	<!-- /.charts -->
</div>


