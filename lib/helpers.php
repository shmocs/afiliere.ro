<?php

namespace yii\helpers;

class VarDumper extends BaseVarDumper {
	
	public static function dump($var, $depth = 10, $highlight = true)
	{
		echo date('Y-m-d H:i:s') .' - '. static::dumpAsString($var, $depth, $highlight) . '<br /><br />';
	}
	
	/**
	 * @param $filename
	 * @param $message
	 */
	public static function writeLog($message, $filename = 'notification.log')
	{
		$log_path = \Yii::getAlias('@webroot').'/protected/api/runtime/';
		$yesterday_filename = str_replace('.log', '.'.date("Y-m-d", strtotime("yesterday")).'.log', $filename); //notification.2015-06-11.log
		
		if (!is_file($log_path . $yesterday_filename)) {
			if (is_file($log_path . $filename)) {
				copy($log_path . $filename, $log_path . $yesterday_filename);
			}
			file_put_contents($log_path . $filename, ''); // no append
		}
		
		$date = date('Y-m-d H:i:s', time());
		$log = $date . " - ";
		$log .= $message;
		$log .= "\n" . str_repeat('-', 100) .  "\n\n\n";
		
		file_put_contents($log_path . $filename, $log, FILE_APPEND);
	}
}