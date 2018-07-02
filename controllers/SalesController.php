<?php

namespace app\controllers;

use app\models\Sale;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

class SalesController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
	    $searchModel = new Sale();
	
	    $params = Yii::$app->request->get();
	
	    $dataProvider = $searchModel->search($params);
	    
	    //echo '<pre>';print_r($dataProvider);echo '</pre>';
	
	    return $this->render(
		    'index',
		    [
			    'dataProvider' => $dataProvider,
			    'searchModel' => $searchModel,
			    'model' => $searchModel,
		    ]
	    );
    }

}
