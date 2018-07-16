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
	
	    $start_date = date('Y-m-d H:i:s', strtotime('last month'));
        $end_date = date('Y-m-d H:i:s');
        $start_date = '2018-06-01';
        $end_date = '2018-07-01';
	    
	    if (isset($params['start_date'])) {
	    	$start_date = $params['start_date'];
	    }
	    if (isset($params['end_date'])) {
	    	$end_date = $params['end_date'];
	    }
    
	    
	    $dataProvider = Reports::getGlobalReport($date_type, $start_date, $end_date);
	    //echo '<pre>';print_r($dataProvider);echo '</pre>';
	    
	    return $this->render(
		    'index',
		    [
			    'dataProvider' => $dataProvider,
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
	    $searchModel = new Sale();
	
	    $params = Yii::$app->request->get();
	
	    $dataProvider = $searchModel->search($params);
	    //echo '<pre>';print_r($dataProvider);echo '</pre>';
	    
	    return $this->render(
		    'advertiser',
		    [
			    'dataProvider' => $dataProvider,
			    'searchModel' => $searchModel,
			    'model' => $searchModel,
		    ]
	    );
    }

}
