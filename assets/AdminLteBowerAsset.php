<?php
namespace app\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class AdminLteBowerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components';
    public $css = [
        //'Ionicons/css/ionicons.min.css',
	    //'datatables.net-bs/css/dataTables.bootstrap.min.css'
    ];
    public $js = [
        //'jquery/dist/jquery.min.js',
	    //'bootstrap/dist/js/bootstrap.min.js',
	    //'datatables.net/js/jquery.dataTables.min.js',
	    //'datatables.net-bs/js/dataTables.bootstrap.min.js',
	    //'jquery-slimscroll/jquery.slimscroll.min.js',
	    //'fastclick/lib/fastclick.js',
    ];
    public $depends = [
        'dmstr\web\AdminLteAsset',
    ];

}
