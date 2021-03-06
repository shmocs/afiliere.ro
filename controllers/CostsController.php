<?php

namespace app\controllers;

use app\models\Cost;
use app\models\Import;
use Yii;
use yii\costs\CostsImport;
use yii\filters\AccessControl;
use yii\helpers\VarDumper;
use yii\web\Controller;
use yii\web\Response;

class CostsController extends Controller
{

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
	    $searchModel = new Cost();
	
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


    public function actionImport()
    {
    	//'filename' => 'danielavaduva-commissions-all (7).csv'
	    //VarDumper::dump($_POST);
	    
	    $response = [
	    	'type' => 'success',
		    'messages' => [],
	    ];
	    
	    if (isset($_POST['filename'])) {

	    	
	    	$import = new CostsImport($_POST['filename']);
	    	
		    $response = $import->result;
		    
	    } else {
		    $response['messages'][] = 'File missing !';
		    $response['type'] = 'error';
	    }
	
	    echo json_encode($response);
	    die();
    }

}
