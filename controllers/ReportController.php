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
	
	    $start_date = date('Y-m-d', strtotime('last month'));
	    $end_date = date('Y-m-d');
	
	
	    if (isset($params['date_range'])) {
		    $date_explode = explode(" - ", $params['date_range']);
		    $start_date = trim($date_explode[0]);
		    $end_date = trim($date_explode[1]);
	    }
    
        $performance_data = \app\models\Sale::getDataChart01($start_date, $end_date);
        //\yii\helpers\VarDumper::dump($performance_data, 10, true);
        

        $advertisers = [];
        
	    return $this->render(
		    'advertiser',
		    [
			    'performance_data' => $performance_data,
			    'advertisers' => $advertisers,
			    'date_range' => $start_date . ' - ' . $end_date,
		    ]
	    );
    }

}
