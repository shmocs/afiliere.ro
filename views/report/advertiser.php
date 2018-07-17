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

$performance_data = json_encode($performance_data);


?>
<!-- Styles -->
<style>
	#chartdiv, #chartdiv_sales, #chartdiv_conversions, #chartdiv_profit {
		width: 100%;
		height: 400px;
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
	var chart_sales = AmCharts.makeChart("chartdiv_sales", {
		"type": "serial",
		// "dataLoader": {
		// 	"url": "sales/chart01",
		// 	"format": "json"
		// },
		"theme": "light",
		"dataDateFormat": "YYYY-MM-DD",
		"precision": 2,
		"valueAxes": [{
			"id": "v1",
			"title": "Sales (LEI)",
			"position": "left",
			"autoGridCount": false,
			"labelFunction": function(value) {
				return "" + Math.round(value, 2) + "";
			}
		}],
		"graphs": [{
			"id": "g1",
			"valueAxis": "v1",
			"lineColor": "#e1ede9",
			"fillColors": "#e1ede9",
			"fillAlphas": 1,
			"type": "column",
			"title": "Total Sales",
			"valueField": "total_sales",
			"clustered": false,
			"columnWidth": 0.5,
			"legendValueText": "[[value]]",
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
			"lineColor": "#20acd4",
			//"type": "smoothedLine",
			"title": "2Performant",
			"useLineColorForBulletBorder": true,
			"valueField": "2Performant_sales_amount",
			"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
		}, {
			"id": "g3",
			"valueAxis": "v2",
			"bullet": "round",
			"bulletBorderAlpha": 1,
			"bulletColor": "#FFFFFF",
			"bulletSize": 5,
			"hideBulletsCount": 50,
			"lineThickness": 2,
			"lineColor": "#ff851b",
			//"type": "smoothedLine",
			//"dashLength": 5,
			"title": "ProfitShare",
			"useLineColorForBulletBorder": true,
			"valueField": "ProfitShare_sales_amount",
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
			"cursorAlpha": 0,
			"valueLineAlpha": 0.2
		},
		"categoryField": "date",
		"categoryAxis": {
			"parseDates": true,
			"dashLength": 1,
			"minorGridEnabled": true
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

		"periodSelector": {
			"position": "top",
			"dateFormat": "YYYY-MM-DD",
			"inputFieldWidth": 100,
			"periods": [{
				"period": "DD",
				"count": 1,
				"label": "1 day"
			}, {
				"period": "DD",
				"count": 7,
				"label": "1 week",
			}, {
				"period": "MM",
				"count": 1,
				"label": "1 month"
			}, {
				"period": "MM",
				"count": 3,
				"label": "3 months"
			}, {
				"period": "MAX",
				"label": "MAX"
			}]
		},

		"dataProvider": <?php echo $performance_data;?>
	});

	var chart_conversions = AmCharts.makeChart("chartdiv_conversions", {
		"type": "serial",
		// "dataLoader": {
		// 	"url": "sales/chart01",
		// 	"format": "json"
		// },
		"theme": "light",
		"dataDateFormat": "YYYY-MM-DD",
		"precision": 2,
		"valueAxes": [
			// {
			// 	"id": "v1",
			// 	"title": "Sales (LEI)",
			// 	"position": "left",
			// 	"autoGridCount": false,
			// 	"labelFunction": function(value) {
			// 		return "" + Math.round(value, 2) + "";
			// 	}
			// },
			{
				"id": "v2",
				"title": "Nr. Conversions",
				"gridAlpha": 0,
				"position": "right",
				"autoGridCount": false
			}],
		"graphs": [
			// {
			// 	"id": "g1",
			// 	"valueAxis": "v1",
			// 	"lineColor": "#e1ede9",
			// 	"fillColors": "#e1ede9",
			// 	"fillAlphas": 1,
			// 	"type": "column",
			// 	"title": "Total Sales",
			// 	"valueField": "total_sales",
			// 	"clustered": false,
			// 	"columnWidth": 0.5,
			// 	"legendValueText": "[[value]]",
			// 	"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
			// },
			{
				"id": "g2",
				"valueAxis": "v2",
				"lineColor": "#62cf73",
				"fillColors": "#e1ede9",
				"fillAlphas": 1,
				"type": "column",
				"title": "Conversions",
				"valueField": "total_conversions",
				"clustered": false,
				"columnWidth": 0.5,
				"legendValueText": "[[value]]",
				"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
			}, {
				"id": "g3",
				"valueAxis": "v2",
				"bullet": "round",
				"bulletBorderAlpha": 1,
				"bulletColor": "#FFFFFF",
				"bulletSize": 5,
				"hideBulletsCount": 50,
				"lineThickness": 2,
				"lineColor": "#20acd4",
				//"type": "smoothedLine",
				"title": "2Performant",
				"useLineColorForBulletBorder": true,
				"valueField": "2Performant_sales_nr",
				"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
			}, {
				"id": "g4",
				"valueAxis": "v2",
				"bullet": "round",
				"bulletBorderAlpha": 1,
				"bulletColor": "#FFFFFF",
				"bulletSize": 5,
				"hideBulletsCount": 50,
				"lineThickness": 2,
				"lineColor": "#ff851b",
				//"type": "smoothedLine",
				//"dashLength": 5,
				"title": "ProfitShare",
				"useLineColorForBulletBorder": true,
				"valueField": "ProfitShare_sales_nr",
				"balloonText": "[[title]]<br /><b style='font-size: 130%'>[[value]]</b>"
			}],
		"chartScrollbar": {
			"graph": "g2",
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
			"cursorAlpha": 0,
			"valueLineAlpha": 0.2
		},
		"categoryField": "date",
		"categoryAxis": {
			"parseDates": true,
			"dashLength": 1,
			"minorGridEnabled": true
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
		"dataProvider": <?php echo $performance_data;?>
	});
</script>

<?php

$advertiser_data = [
	'clicks' => 4529,
	'conversions' => 527,
	'commision_amount' => 4738.00,
	'cost' => 2147.33,
	'profit' => 2590.67,
	'conversion_rate' => 70,
	'revenue_click' => 4.44,
	'cpc' => 3.12,
	'profit_click' => 2.44,
	'time_lag' => '',
	'rma_volum' => '87%',
	'rma_value' => '90%',
];

if ($advertiser_data['conversion_rate'] >= 80) {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-up';
    $advertiser_data['conversion_rate_type'] = 'green';
} else if ($advertiser_data['conversion_rate'] >= 70) {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-up';
    $advertiser_data['conversion_rate_type'] = 'orange';
} else {
    $advertiser_data['conversion_rate_thumbs'] = 'fa-thumbs-o-down';
    $advertiser_data['conversion_rate_type'] = 'red';
}

if ($advertiser_data['cost'] > 0) {
	$advertiser_data['roas'] = number_format($advertiser_data['commision_amount'] / $advertiser_data['cost'], 2, '.', '');
	if ($advertiser_data['roas'] > 1) {
        $advertiser_data['roas_type'] = 'green';
	} else {
        $advertiser_data['roas_type'] = 'orange';
	}
} else {
	$advertiser_data['roas'] = 0;
    $advertiser_data['roas_type'] = 'aqua';
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
							<select name="advertiser" id="advertiser">
								<option value="click_date" <?php if ($advertiser == 'adv1') echo 'selected="selected"';?>>adv1</option>
								<option value="conversion_date" <?php if ($advertiser == 'adv2') echo 'selected="selected"';?>>adv2</option>
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
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-money"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Commision Amount</span>
						<span class="info-box-number"><?=$advertiser_data['commision_amount'];?></span>
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
					<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Profit</span>
						<span class="info-box-number"><?=$advertiser_data['profit'];?></span>
					</div>
				</div>
			</div>
			
			<div class="clearfix"></div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box bg-<?=$advertiser_data['conversion_rate_type'];?>">
					<span class="info-box-icon"><i class="fa <?=$advertiser_data['conversion_rate_thumbs'];?>"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Conversion Rate</span>
						<span class="info-box-number"><?=$advertiser_data['clicks'];?> / <?=$advertiser_data['conversions'];?></span>
						
						<div class="progress">
							<div class="progress-bar" style="width: <?=$advertiser_data['conversion_rate'];?>%"></div>
						</div>
						<span class="progress-description">
                        <?=$advertiser_data['conversion_rate'];?>%
                  </span>
					</div>
					<!-- /.info-box-content -->
				</div>
				<!-- /.info-box -->
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-money"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Revenue/Click</span>
						<span class="info-box-number"><?=$advertiser_data['revenue_click'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-credit-card"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">CPC</span>
						<span class="info-box-number"><?=$advertiser_data['cpc'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-dollar"></i><i class="fa fa-mouse-pointer"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">Profit/Click</span>
						<span class="info-box-number"><?=$advertiser_data['profit_click'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-<?=$advertiser_data['roas_type'];?>"><i class="fa fa-star"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">ROAS</span>
						<span class="info-box-number"><?=$advertiser_data['roas'];?></span>
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
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">RMA Volume</span>
						<span class="info-box-number"><?=$advertiser_data['rma_volum'];?></span>
					</div>
				</div>
			</div>
			
			<div class="col-md-2 col-sm-6 col-xs-12">
				<div class="info-box">
					<span class="info-box-icon bg-aqua"><i class="fa fa-calendar-check-o"></i></span>
					
					<div class="info-box-content">
						<span class="info-box-text">RMA Value</span>
						<span class="info-box-number"><?=$advertiser_data['rma_value'];?></span>
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
						<h3 class="box-title pull-left">Sales Performance</h3>
						
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body chart-responsive">
						<div id="chartdiv_sales"></div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			
			</div>
			
			<div class="col-md-6">
				
				<!-- LINE CHART -->
				<div class="box box-info">
					<div class="box-header with-border">
						<h3 class="box-title">Conversions Performance</h3>
						
						<div class="box-tools pull-right">
							<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
							</button>
							<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
						</div>
					</div>
					<div class="box-body chart-responsive">
						<div id="chartdiv_conversions"></div>
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			
			</div>
		</div>
	</section>
	<!-- /.charts -->
</div>


