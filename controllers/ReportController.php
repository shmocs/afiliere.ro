<?php

namespace app\controllers;

use app\models\Sale;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\reports\Reports;
use yii\sales\SalesImport;
use yii\web\Controller;
use yii\web\Response;

class ReportController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
	    $params = Yii::$app->request->get();
	
	    $date_type = 'click_date';
	    if (isset($params['date_type'])) {
	    	$date_type = $params['date_type'];
	    }
	
	    $start_date = date('Y-m-d', strtotime('last month'));
        $end_date = date('Y-m-d');
	
        
	    if (isset($params['date_range'])) {
		    $date_explode = explode(" - ", $params['date_range']);
		    $start_date = trim($date_explode[0]);
		    $end_date = trim($date_explode[1]);
		}
    
	    
	    $dataProvider = Reports::getGlobalReport($date_type, $start_date, $end_date);
	    //echo '<pre>';print_r($dataProvider);echo '</pre>';
	    
	    return $this->render(
		    'index',
		    [
			    'dataProvider' => $dataProvider,
			    'date_type' => $date_type,
			    'date_range' => $start_date . ' - ' . $end_date,
		    ]
	    );
    }

    /**
     * Displays report.
     *
     * @return string
     */
    public function actionAdvertiser()
    {
	    $params = Yii::$app->request->get();
	
	    $date_type = 'click_date';
	    if (isset($params['date_type'])) {
		    $date_type = $params['date_type'];
	    }
    
        $chartdiv_profit_interval = 1;
	    if (isset($params['chartdiv_profit_interval'])) {
            $chartdiv_profit_interval = $params['chartdiv_profit_interval'];
	    }
	    
        $commission_type = 'accepted';
	    if (isset($params['commission_type'])) {
            $commission_type = $params['commission_type'];
	    }
	    
	    $start_date = date('Y-m-d', strtotime('last month'));
	    $end_date = date('Y-m-d');
	    if (isset($params['date_range'])) {
		    $date_explode = explode(" - ", $params['date_range']);
		    $start_date = trim($date_explode[0]);
		    $end_date = trim($date_explode[1]);
	    }
	
	
	    $advertisers = Sale::find()->select('advertiser')->distinct()->asArray()->all();
	    $advertiser = $advertisers[0]['advertiser'];
	    if (isset($params['advertiser'])) {
		    $advertiser = $params['advertiser'];
	    }
	
	    $profits_data = Reports::getAdvertiserDataChartProfits($advertiser, $date_type, $commission_type, $chartdiv_profit_interval, $start_date, $end_date);
        //\yii\helpers\VarDumper::dump($performance_data, 10, true);
	
	    $ROAS_data = Reports::getAdvertiserDataChartROAS($advertiser, $date_type, $commission_type, $start_date, $end_date);
        //\yii\helpers\VarDumper::dump($performance_data, 10, true);
	
	    $advertiser_data = Reports::getAdvertiserReport($advertiser, $date_type, $commission_type, $start_date, $end_date);
	    //VarDumper::dump($advertiser_data, 10, true);
	    
	    return $this->render(
		    'advertiser',
		    [
			    'profits_data' => $profits_data,
			    'ROAS_data' => $ROAS_data,
			    'advertiser_data' => $advertiser_data,
			    'advertisers' => $advertisers,
			    'advertiser' => $advertiser,
			    'date_type' => $date_type,
			    'commission_type' => $commission_type,
			    'date_range' => $start_date . ' - ' . $end_date,
			    'chartdiv_profit_interval' => $chartdiv_profit_interval,
		    ]
	    );
    }

}
