<?php

$data = [
	[
		'date' => '2017-11-02',
		'total' => 15,
		'2p' => 0,
		'ps' => 15,
	],
	[
		'date' => '2017-11-03',
		'total' => 11,
		'2p' => 3,
		'ps' => 8,
	],
	[
		'date' => '2017-11-08',
		'total' => 5,
		'2p' => 4,
		'ps' => 1,
	],
	[
		'date' => '2017-11-09',
		'total' => 3,
		'2p' => 0,
		'ps' => 3,
	],
	[
		'date' => '2017-11-11',
		'total' => 5,
		'2p' => 2,
		'ps' => 3,
	],
];

//$sales = \app\models\Sale::getAllForCharts();
//\yii\helpers\VarDumper::dump($sales, 10, true);

$data = \app\models\Sale::getDataChart01();
//\yii\helpers\VarDumper::dump($data, 10, true);

$json_data = json_encode($data);


$profits_data = \yii\reports\Reports::getDataChartProfits();
//\yii\helpers\VarDumper::dump($profits_data, 10, true);
$profits_json = json_encode($profits_data);

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
		
		"dataProvider": <?php echo $json_data;?>
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
		"dataProvider": <?php echo $json_data;?>
	});




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
			"labelRotation": 45
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




	<!-- Chart code -->

	var chartData1 = [];
	var chartData2 = [];
	var chartData3 = [];
	var chartData4 = [];

	generateChartData();

	function generateChartData() {
		var firstDate = new Date();
		firstDate.setDate( firstDate.getDate() - 500 );
		firstDate.setHours( 0, 0, 0, 0 );

		var a1 = 1500;
		var b1 = 1500;
		var a2 = 1700;
		var b2  = 1700;
		var a3 = 1600;
		var b3 = 1600;
		var a4 = 1400;
		var b4 = 1400;

		for ( var i = 0; i < 500; i++ ) {
			var newDate = new Date( firstDate );
			newDate.setDate( newDate.getDate() + i );

			a1 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
			b1 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

			a2 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
			b2 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

			a3 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
			b3 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

			a4 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);
			b4 += Math.round((Math.random()<0.5?1:-1)*Math.random()*10);

			chartData1.push( {
				"date": newDate,
				"value": a1,
				"volume": b1 + 1500
			} );
			chartData2.push( {
				"date": newDate,
				"value": a2,
				"volume": b2 + 1500
			} );
			chartData3.push( {
				"date": newDate,
				"value": a3,
				"volume": b3 + 1500
			} );
			chartData4.push( {
				"date": newDate,
				"value": a4,
				"volume": b4 + 1500
			} );
		}
	};

	if (0)
	var chart = AmCharts.makeChart( "chartdiv", {
		"type": "stock",
		"theme": "light",
		"dataSets": [ {
			"title": "first data set",
			"fieldMappings": [ {
				"fromField": "value",
				"toField": "value"
			}, {
				"fromField": "volume",
				"toField": "volume"
			} ],
			"dataProvider": chartData1,
			"categoryField": "date"
		}, {
			"title": "second data set",
			"fieldMappings": [ {
				"fromField": "value",
				"toField": "value"
			}, {
				"fromField": "volume",
				"toField": "volume"
			} ],
			"dataProvider": chartData2,
			"categoryField": "date"
		}, {
			"title": "third data set",
			"fieldMappings": [ {
				"fromField": "value",
				"toField": "value"
			}, {
				"fromField": "volume",
				"toField": "volume"
			} ],
			"dataProvider": chartData3,
			"categoryField": "date"
		}, {
			"title": "fourth data set",
			"fieldMappings": [ {
				"fromField": "value",
				"toField": "value"
			}, {
				"fromField": "volume",
				"toField": "volume"
			} ],
			"dataProvider": chartData4,
			"categoryField": "date"
		}
		],

		"panels": [ {
			"showCategoryAxis": false,
			"title": "Value",
			"percentHeight": 70,
			"stockGraphs": [ {
				"id": "g1",
				"valueField": "value",
				"comparable": true,
				"compareField": "value",
				"balloonText": "[[title]]:<b>[[value]]</b>",
				"compareGraphBalloonText": "[[title]]:<b>[[value]]</b>"
			} ],
			"stockLegend": {
				"periodValueTextComparing": "[[percents.value.close]]%",
				"periodValueTextRegular": "[[value.close]]"
			}
		}, {
			"title": "Volume",
			"percentHeight": 30,
			"stockGraphs": [ {
				"valueField": "volume",
				"type": "column",
				"showBalloon": false,
				"fillAlphas": 1
			} ],
			"stockLegend": {
				"periodValueTextRegular": "[[value.close]]"
			}
		} ],

		"chartScrollbarSettings": {
			"graph": "g1"
		},

		"chartCursorSettings": {
			"valueBalloonsEnabled": true,
			"fullWidth": true,
			"cursorAlpha": 0.1,
			"valueLineBalloonEnabled": true,
			"valueLineEnabled": true,
			"valueLineAlpha": 0.5
		},

		"periodSelector": {
			"position": "left",
			"periods": [ {
				"period": "MM",
				"selected": true,
				"count": 1,
				"label": "1 month"
			}, {
				"period": "YYYY",
				"count": 1,
				"label": "1 year"
			}, {
				"period": "YTD",
				"label": "YTD"
			}, {
				"period": "MAX",
				"label": "MAX"
			} ]
		},

		"dataSetSelector": {
			"position": "top"
		},

		"export": {
			"enabled": true
		}
	} );
	
</script>


<!-- HTML -->
<div class="row">
	<div class="col-md-6">
		
		<!-- LINE CHART -->
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Sales Performance</h3>
				
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
		<div class="box box-primary">
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


<div class="row">
	<div class="col-md-6">
		
		<!-- LINE CHART -->
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Profits</h3>
				
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
		<div class="box box-warning">
			<div class="box-header with-border">
				<h3 class="box-title">Best Graph</h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive">
				<div id="chartdiv"></div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
		
	</div>
</div>


<div class="hidden_container hidden">
<div class="row">
	<div class="col-md-6">
		<!-- AREA CHART -->
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Area Chart</h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive">
				<div class="chart" id="revenue-chart" style="height: 300px;"><svg height="300" version="1.1" width="741.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; top: -0.700012px;"><desc>Created with Raphaël 2.2.0</desc><defs></defs><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="261" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">0</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,261H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="202" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">7,500</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,202H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="143" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">15,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,143H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="84.00000000000003" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4.000000000000028">22,500</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,84.00000000000003H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="25.00000000000003" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4.000000000000028">30,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,25.00000000000003H716.5" stroke-width="0.5"></path><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="597.1763455014936" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2013</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="307.87494304085874" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2012</tspan></text><path style="fill-opacity: 1;" fill="#74a5c2" stroke="none" d="M66,219.05493333333334C84.17190901726495,219.56626666666668,120.51572705179485,222.6231049981125,138.6876360690598,221.10026666666667C156.8677788466407,219.57673833144582,193.22806440180244,209.1350666666667,211.40820717938334,206.86946666666668C229.39897346969775,204.6274666666667,265.3805060503266,204.88256480506598,283.371272340641,203.06986666666666C301.35380487063946,201.2579981383993,337.3188699306364,194.91349669779092,355.3014024606349,192.3712C373.47331147789987,189.80213003112425,409.81712951242974,182.51724094375237,427.9890385296947,182.6244C446.1691813072756,182.73160761041905,482.52946686243735,204.17807818499128,500.70960964001824,193.22866666666667C518.7003759303327,182.39331151832462,554.6819085109614,101.94542370540853,572.6726748012759,95.48533333333336C590.457597083692,89.09915703874188,626.0274416485241,135.13648756583467,643.8123639309403,141.8436C661.9842729482052,148.69665423250134,698.328090982735,147.7554,716.5,149.726L716.5,261L66,261Z" fill-opacity="1"></path><path style="" fill="none" stroke="#3c8dbc" d="M66,219.05493333333334C84.17190901726495,219.56626666666668,120.51572705179485,222.6231049981125,138.6876360690598,221.10026666666667C156.8677788466407,219.57673833144582,193.22806440180244,209.1350666666667,211.40820717938334,206.86946666666668C229.39897346969775,204.6274666666667,265.3805060503266,204.88256480506598,283.371272340641,203.06986666666666C301.35380487063946,201.2579981383993,337.3188699306364,194.91349669779092,355.3014024606349,192.3712C373.47331147789987,189.80213003112425,409.81712951242974,182.51724094375237,427.9890385296947,182.6244C446.1691813072756,182.73160761041905,482.52946686243735,204.17807818499128,500.70960964001824,193.22866666666667C518.7003759303327,182.39331151832462,554.6819085109614,101.94542370540853,572.6726748012759,95.48533333333336C590.457597083692,89.09915703874188,626.0274416485241,135.13648756583467,643.8123639309403,141.8436C661.9842729482052,148.69665423250134,698.328090982735,147.7554,716.5,149.726" stroke-width="3"></path><circle cx="66" cy="219.05493333333334" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="138.6876360690598" cy="221.10026666666667" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="211.40820717938334" cy="206.86946666666668" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="283.371272340641" cy="203.06986666666666" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="355.3014024606349" cy="192.3712" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="427.9890385296947" cy="182.6244" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="500.70960964001824" cy="193.22866666666667" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="572.6726748012759" cy="95.48533333333336" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="643.8123639309403" cy="141.8436" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="716.5" cy="149.726" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><path style="fill-opacity: 1;" fill="#eaf3f6" stroke="none" d="M66,240.02746666666667C84.17190901726495,239.8072,120.51572705179485,241.35446642506605,138.6876360690598,239.1464C156.8677788466407,236.93733309173274,193.22806440180244,223.3365417102967,211.40820717938334,222.35893333333334C229.39897346969775,221.39150837696334,265.3805060503266,233.23306051728085,283.371272340641,231.36626666666666C301.35380487063946,229.5003271839475,337.3188699306364,209.28948157595082,355.3014024606349,207.428C373.47331147789987,205.54691490928414,409.81712951242974,214.43960989052474,427.9890385296947,216.39600000000002C446.1691813072756,218.3532765571914,482.52946686243735,232.37735986038396,500.70960964001824,223.08266666666668C518.7003759303327,213.8847931937173,554.6819085109614,148.22814457230533,572.6726748012759,142.42573333333334C590.457597083692,136.689711238972,626.0274416485241,170.46889944279064,643.8123639309403,176.92893333333336C661.9842729482052,183.52953277612397,698.328090982735,190.23343333333335,716.5,194.66826666666668L716.5,261L66,261Z" fill-opacity="1"></path><path style="" fill="none" stroke="#a0d0e0" d="M66,240.02746666666667C84.17190901726495,239.8072,120.51572705179485,241.35446642506605,138.6876360690598,239.1464C156.8677788466407,236.93733309173274,193.22806440180244,223.3365417102967,211.40820717938334,222.35893333333334C229.39897346969775,221.39150837696334,265.3805060503266,233.23306051728085,283.371272340641,231.36626666666666C301.35380487063946,229.5003271839475,337.3188699306364,209.28948157595082,355.3014024606349,207.428C373.47331147789987,205.54691490928414,409.81712951242974,214.43960989052474,427.9890385296947,216.39600000000002C446.1691813072756,218.3532765571914,482.52946686243735,232.37735986038396,500.70960964001824,223.08266666666668C518.7003759303327,213.8847931937173,554.6819085109614,148.22814457230533,572.6726748012759,142.42573333333334C590.457597083692,136.689711238972,626.0274416485241,170.46889944279064,643.8123639309403,176.92893333333336C661.9842729482052,183.52953277612397,698.328090982735,190.23343333333335,716.5,194.66826666666668" stroke-width="3"></path><circle cx="66" cy="240.02746666666667" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="138.6876360690598" cy="239.1464" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="211.40820717938334" cy="222.35893333333334" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="283.371272340641" cy="231.36626666666666" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="355.3014024606349" cy="207.428" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="427.9890385296947" cy="216.39600000000002" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="500.70960964001824" cy="223.08266666666668" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="572.6726748012759" cy="142.42573333333334" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="643.8123639309403" cy="176.92893333333336" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="716.5" cy="194.66826666666668" r="4" fill="#a0d0e0" stroke="#ffffff" style="" stroke-width="1"></circle></svg><div class="morris-hover morris-default-style" style="left: 22.5px; top: 154px; display: none;"><div class="morris-hover-row-label">2011 Q1</div><div class="morris-hover-point" style="color: #a0d0e0">
							Item 1:
							2,666
						</div><div class="morris-hover-point" style="color: #3c8dbc">
							Item 2:
							2,666
						</div></div></div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
		
		<!-- DONUT CHART -->
		<div class="box box-danger">
			<div class="box-header with-border">
				<h3 class="box-title">Donut Chart</h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive">
				<div class="chart" id="sales-chart" style="height: 300px; position: relative;"><svg height="300" version="1.1" width="741.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; top: -0.200012px;"><desc>Created with Raphaël 2.2.0</desc><defs></defs><path style="opacity: 0;" fill="none" stroke="#3c8dbc" d="M370.75,243.33333333333331A93.33333333333333,93.33333333333333,0,0,0,458.9777551949771,180.44625304313007" stroke-width="2" opacity="0"></path><path style="" fill="#3c8dbc" stroke="#ffffff" d="M370.75,246.33333333333331A96.33333333333333,96.33333333333333,0,0,0,461.81364732624417,181.4248826052307L498.3651459070204,194.03833029452744A135,135,0,0,1,370.75,285Z" stroke-width="3"></path><path style="opacity: 1;" fill="none" stroke="#f56954" d="M458.9777551949771,180.44625304313007A93.33333333333333,93.33333333333333,0,0,0,287.03484627831415,108.73398312817662" stroke-width="2" opacity="1"></path><path style="" fill="#f56954" stroke="#ffffff" d="M461.81364732624417,181.4248826052307A96.33333333333333,96.33333333333333,0,0,0,284.34400205154566,107.40757544301087L245.17726941747117,88.10097469226493A140,140,0,0,1,503.0916327924656,195.6693795646951Z" stroke-width="3"></path><path style="opacity: 0;" fill="none" stroke="#00a65a" d="M287.03484627831415,108.73398312817662A93.33333333333333,93.33333333333333,0,0,0,370.72067846904883,243.333328727518" stroke-width="2" opacity="0"></path><path style="" fill="#00a65a" stroke="#ffffff" d="M284.34400205154566,107.40757544301087A96.33333333333333,96.33333333333333,0,0,0,370.71973599126824,246.3333285794739L370.7075884998742,284.9999933380171A135,135,0,0,1,249.6620097954186,90.31165416754118Z" stroke-width="3"></path><text style="text-anchor: middle; font-family: &quot;Arial&quot;; font-size: 15px; font-weight: 800;" x="370.75" y="140" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="15px" stroke="none" fill="#000000" font-weight="800" transform="matrix(1.4118,0,0,1.4118,-152.8676,-61.3529)" stroke-width="0.7083333333333333"><tspan dy="5">In-Store Sales</tspan></text><text style="text-anchor: middle; font-family: &quot;Arial&quot;; font-size: 14px;" x="370.75" y="160" text-anchor="middle" font-family="&quot;Arial&quot;" font-size="14px" stroke="none" fill="#000000" transform="matrix(1.9444,0,0,1.9444,-350.625,-143.5556)" stroke-width="0.5142857142857143"><tspan dy="5">30</tspan></text></svg></div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	
	</div>
	<!-- /.col (LEFT) -->
	<div class="col-md-6">
		<!-- LINE CHART -->
		<div class="box box-info">
			<div class="box-header with-border">
				<h3 class="box-title">Line Chart</h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive">
				<div class="chart" id="line-chart" style="height: 300px;"><svg height="300" version="1.1" width="741.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.5px; top: -0.700012px;"><desc>Created with Raphaël 2.2.0</desc><defs></defs><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="261" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">0</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,261H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="202" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">5,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,202H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="143" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">10,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,143H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="84" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">15,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,84H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="53.5" y="25" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">20,000</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M66,25H716.5" stroke-width="0.5"></path><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="597.1763455014936" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2013</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="307.87494304085874" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2012</tspan></text><path style="" fill="none" stroke="#3c8dbc" d="M66,229.5412C84.17190901726495,229.2108,120.51572705179485,231.5316996375991,138.6876360690598,228.2196C156.8677788466407,224.9059996375991,193.22806440180244,204.50481256544504,211.40820717938334,203.0384C229.39897346969775,201.58726256544503,265.3805060503266,219.34959077592126,283.371272340641,216.5494C301.35380487063946,213.75049077592126,337.3188699306364,183.4342223639262,355.3014024606349,180.642C373.47331147789987,177.8203723639262,409.81712951242974,191.1594148357871,427.9890385296947,194.094C446.1691813072756,197.02991483578708,482.52946686243735,218.0660397905759,500.70960964001824,204.124C518.7003759303327,190.32718979057591,554.6819085109614,91.84221685845799,572.6726748012759,83.1386C590.457597083692,74.53456685845799,626.0274416485241,125.20334916418592,643.8123639309403,134.89339999999999C661.9842729482052,144.79429916418593,698.328090982735,154.85015,716.5,161.50240000000002" stroke-width="3"></path><circle cx="66" cy="229.5412" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="138.6876360690598" cy="228.2196" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="211.40820717938334" cy="203.0384" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="283.371272340641" cy="216.5494" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="355.3014024606349" cy="180.642" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="427.9890385296947" cy="194.094" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="500.70960964001824" cy="204.124" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="572.6726748012759" cy="83.1386" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="643.8123639309403" cy="134.89339999999999" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle><circle cx="716.5" cy="161.50240000000002" r="4" fill="#3c8dbc" stroke="#ffffff" style="" stroke-width="1"></circle></svg><div class="morris-hover morris-default-style" style="left: 22.5px; top: 162px; display: none;"><div class="morris-hover-row-label">2011 Q1</div><div class="morris-hover-point" style="color: #3c8dbc">
							Item 1:
							2,666
						</div></div></div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
		
		<!-- BAR CHART -->
		<div class="box box-success">
			<div class="box-header with-border">
				<h3 class="box-title">Bar Chart</h3>
				
				<div class="box-tools pull-right">
					<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body chart-responsive">
				<div class="chart" id="bar-chart" style="height: 300px;"><svg height="300" version="1.1" width="741.5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="overflow: hidden; position: relative; left: -0.5px; top: -0.200012px;"><desc>Created with Raphaël 2.2.0</desc><defs></defs><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="36.5" y="261" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">0</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M49,261H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="36.5" y="202" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">25</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M49,202H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="36.5" y="143" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">50</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M49,143H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="36.5" y="84" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">75</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M49,84H716.5" stroke-width="0.5"></path><text style="text-anchor: end; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="36.5" y="25" text-anchor="end" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal"><tspan dy="4">100</tspan></text><path style="" fill="none" stroke="#aaaaaa" d="M49,25H716.5" stroke-width="0.5"></path><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="668.8214285714286" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2012</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="573.4642857142857" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2011</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="478.10714285714283" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2010</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="382.75" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2009</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="287.3928571428571" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2008</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="192.03571428571428" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2007</tspan></text><text style="text-anchor: middle; font-family: sans-serif; font-size: 12px; font-weight: normal;" x="96.67857142857143" y="273.5" text-anchor="middle" font-family="sans-serif" font-size="12px" stroke="none" fill="#888888" font-weight="normal" transform="matrix(1,0,0,1,0,7)"><tspan dy="4">2006</tspan></text><rect x="60.91964285714286" y="25" width="34.25892857142857" height="236" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="98.17857142857143" y="48.60000000000002" width="34.25892857142857" height="212.39999999999998" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="156.27678571428572" y="84" width="34.25892857142857" height="177" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="193.53571428571428" y="107.6" width="34.25892857142857" height="153.4" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="251.63392857142858" y="143" width="34.25892857142857" height="118" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="288.89285714285717" y="166.60000000000002" width="34.25892857142857" height="94.39999999999998" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="346.99107142857144" y="84" width="34.25892857142857" height="177" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="384.25" y="107.6" width="34.25892857142857" height="153.4" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="442.34821428571433" y="143" width="34.25892857142857" height="118" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="479.6071428571429" y="166.60000000000002" width="34.25892857142857" height="94.39999999999998" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="537.7053571428572" y="84" width="34.25892857142857" height="177" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="574.9642857142858" y="107.6" width="34.25892857142857" height="153.4" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="633.0625" y="25" width="34.25892857142857" height="236" rx="0" ry="0" fill="#00a65a" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect><rect x="670.3214285714286" y="48.60000000000002" width="34.25892857142857" height="212.39999999999998" rx="0" ry="0" fill="#f56954" stroke="none" style="fill-opacity: 1;" fill-opacity="1"></rect></svg><div class="morris-hover morris-default-style" style="display: none;"></div></div>
			</div>
			<!-- /.box-body -->
		</div>
		<!-- /.box -->
	
	</div>
	<!-- /.col (RIGHT) -->
</div>

<div class="row">
    <div class="col-md-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    <?= YII_ENV ?>
                </h3>

                <p>
                    Go to Frontend
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-home"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to('site/index') ?>" class="small-box-footer">
                Homepage <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->


    <div class="col-md-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    n/a
                </h3>

                <p>
                    Users
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to(['/user/admin']) ?>" class="small-box-footer">
                Manage <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->

    <div class="col-md-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-orange">
            <div class="inner">
                <h3>
                    <?= count(\Yii::$app->getModules()) ?>
                </h3>

                <p>
                    Modules
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to(['/debug']) ?>" class="small-box-footer">
                Debug <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>

    </div>
    <!-- ./col -->

    <div class="col-md-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    <?= getenv('APP_VERSION') ?>
                </h3>

                <p>
                    Version
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-grid"></i>
            </div>
            <a href="<?= \yii\helpers\Url::to('http://phundament.com') ?>" target="_blank" class="small-box-footer">
                Phundament Online <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <!-- ./col -->

</div>

<div class="row">
    <div class="col-sm-12">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Languages</h3>
            </div>
            <div class="box-body">
                Test
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <small>Registered in <code>urlManager</code> application component.</small>
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </div>

</div>


<div class="row">
    <div class="col-sm-6">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Modules</h3>
            </div>
            <div class="box-body">
                <?php
                foreach (\Yii::$app->getModules() AS $name => $m) {
                    $module = \Yii::$app->getModule($name);
                    echo yii\helpers\Html::a(
                        $module->id,
                        ['/'.$module->id],
                        ['class' => 'btn btn-default btn-flat']
                    );
                }
                ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <small>Registered in application from configuration or bootstrapping.</small>
            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </div>

    <div class="col-sm-6">
        <!-- Default box -->
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Documentation</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-info">
                    <i class="fa fa-warning"></i>
                    <b>Notice!</b> Use the <i>yii2-apidoc</i> extension to
                    create the HTML documentation for this application.
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">

            </div>
            <!-- /.box-footer-->
        </div>
        <!-- /.box -->
    </div>
</div>
</div>

<?if (0) echo $this->render('_expand-collapse'); ?>


