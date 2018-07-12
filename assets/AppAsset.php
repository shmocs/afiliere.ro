<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
	    'jQueryFileUpload/css/jquery.fileupload.css',
    ];
    public $js = [
    	'js/main.js',
    	'js/sales.js',
    	'js/costs.js',
    	'jQueryFileUpload/js/jquery.fileupload.js',
    	'jQueryFileUpload/js/jquery.fileupload-process.js',
    	'jQueryFileUpload/js/jquery.fileupload-validate.js',
    	'js/import.js',
    ];
    public $depends = [
	    'yii\jui\JuiAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
