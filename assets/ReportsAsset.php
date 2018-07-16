<?php
namespace app\assets;

use yii\base\Exception;
use yii\web\AssetBundle;

/**
 * AdminLte AssetBundle
 * @since 0.1
 */
class ReportsAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/reports.js'
    ];
	
	public $depends = [
		'dmstr\web\AdminLteAsset',
	];
}
