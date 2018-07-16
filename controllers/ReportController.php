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
	    
	    $dataProvider = Reports::getGlobalReport($date_type);
	    //echo '<pre>';print_r($dataProvider);echo '</pre>';
	    
	    return $this->render(
		    'index',
		    [
			    //'dataProvider' => $dataProvider,
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
