<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 7/9/2018
 * Time: 2:05 AM
 */

namespace yii\reports;

use app\models\Sale;
use app\models\Cost;
use Yii;
use yii\db\Exception;

class Reports
{
	
	public function __construct($filename)
	{

	}
    
	
    public static function getDataChartProfits() {
        // grafic general cu veniturile ( comisioane acceptate) si cheltuielile
        // cu granulatie lunara
        // la comisioane - dupa data clicului
        
        $sql = "
        SELECT `virt`.*
        FROM (
            (
                SELECT
                    DATE_FORMAT(`click_date`, '%Y-%m') as `YM`,
                    SUM(`amount`) as `monthly_total`,
                    'sale' AS `value_type`
                FROM `sale`
                WHERE `status` = 'accepted'
                GROUP BY YM
                
            ) UNION ALL (
            
                SELECT
                    DATE_FORMAT(`campaign_date`, '%Y-%m') as `YM`,
                    SUM(`cost`) as monthly_total,
                    'cost' AS `value_type`
                FROM `cost`
                GROUP BY `YM`
            )
        ) AS `virt`
        
        ORDER BY `virt`.`YM` ASC
    	";
        
        $profit_rows = Yii::$app->db->createCommand($sql)->queryAll();
        //\yii\helpers\VarDumper::dump($profit_rows, 10, true);
        
        $data = $record = [];
        $keep_date = '';
        
        foreach ($profit_rows as $row) {
            
            if ($keep_date != $row['YM']) {
                
                if (!empty($keep_date)) {
                    $data[] = $record[$keep_date];
                }
                
                $keep_date = $row['YM'];
                $record = [
                    $keep_date => [
                        'date' => $keep_date,
                        'sales' => 0,
                        'costs' => 0,
                        'profit' => 0,
                    ]
                ];
                
            }
            
            if ($row['value_type'] == 'sale') {
                $record[$keep_date]['sales'] = (double)$row['monthly_total'];
                $record[$keep_date]['profit'] += $row['monthly_total'];
            }
            if ($row['value_type'] == 'cost') {
                $record[$keep_date]['costs'] = (double)$row['monthly_total'];
                $record[$keep_date]['profit'] -= $row['monthly_total'];
            }
        }
        $data[] = $record[$keep_date];
        
        return $data;
    }
    
    
    public static function getGlobalReport($date_type = 'click_date', $start_date, $end_date) {
    
		
	    $sql = "
			SELECT
        		`c`.`advertiser`,
        		SUM(`c`.`cost`) AS `cost`
        		
            FROM `cost` `c`
            WHERE 1
            	AND `c`.`campaign_date` BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY `c`.`advertiser`
        	ORDER BY `c`.`advertiser` ASC
		";
	    $costs = Yii::$app->db->createCommand($sql)->queryAll();
	    //\yii\helpers\VarDumper::dump($costs, 10, true);
		
	    $sql = "
			SELECT
        		`advertiser`,
            	`valoare_comisioane_aprobate`,
            	`valoare_comisioane_asteptare`,
            	`valoare_comisioane_anulate`,
            	`valoare_comisioane_total`,
            	
            	`volum_comisioane_aprobate`,
            	`volum_comisioane_asteptare`,
            	`volum_comisioane_anulate`,
            	`volum_comisioane_total`
            FROM (
                SELECT
                    `s`.`advertiser`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'accepted' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_aprobate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'pending' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_asteptare`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'rejected' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_anulate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` IS NOT NULL THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_total`,
                    
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'accepted' THEN 1 END ), 0) AS `volum_comisioane_aprobate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'pending' THEN 1 END ), 0) AS `volum_comisioane_asteptare`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'rejected' THEN 1 END ), 0) AS `volum_comisioane_anulate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` IS NOT NULL THEN 1 END ), 0) AS `volum_comisioane_total`
                    
                FROM `sale` `s`
                LEFT JOIN
                (
                    SELECT * FROM `sale`
                ) AS `s2` ON `s`.`id` = `s2`.`id` AND `s`.`{$date_type}` BETWEEN '{$start_date}' AND '{$end_date}'
                WHERE 1
                
                GROUP BY `s`.`advertiser`
                
            ) AS `sums`

        	ORDER BY `advertiser` ASC
		";
	    $sales = Yii::$app->db->createCommand($sql)->queryAll();
	    //\yii\helpers\VarDumper::dump($sql, 10, true);
	    //\yii\helpers\VarDumper::dump($sales, 10, true);
		
	
		$sql = "
			SELECT
        		`advertiser`,
        		GROUP_CONCAT(`YM`, '|', `valoare_comisioane_aprobate`) AS `value_accepted_details`,
        		GROUP_CONCAT(`YM`, '|', `volum_comisioane_aprobate`) AS `volume_accepted_details`,
        		
        		GROUP_CONCAT(`YM`, '|', `valoare_comisioane_anulate`) AS `value_rejected_details`,
        		GROUP_CONCAT(`YM`, '|', `volum_comisioane_anulate`) AS `volume_rejected_details`,
        		
        		IFNULL(AVG(valoare_comisioane_aprobate), 0) AS `valoare_comisioane_aprobate_avg`,
        		IFNULL(AVG(volum_comisioane_aprobate), 0) AS `volum_comisioane_aprobate_avg`,
        		
        		IFNULL(AVG(valoare_comisioane_anulate), 0) AS `valoare_comisioane_anulate_avg`,
        		IFNULL(AVG(volum_comisioane_anulate), 0) AS `volum_comisioane_anulate_avg`
            FROM (
                SELECT
                	DATE_FORMAT(`s`.`click_date`, '%Y-%m') as `YM`,
                    `s`.`advertiser`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'accepted' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_aprobate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'rejected' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_anulate`,
                    
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'accepted' THEN 1 END ), 0) AS `volum_comisioane_aprobate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'rejected' THEN 1 END ), 0) AS `volum_comisioane_anulate`
                    
                FROM `sale` `s`
                LEFT JOIN
                (
                    SELECT * FROM `sale`
                ) AS `s2` ON `s`.`id` = `s2`.`id`
                WHERE 1
                	AND `s`.`{$date_type}` BETWEEN NOW() - INTERVAL 4 MONTH AND NOW()
                	
                GROUP BY `s`.`advertiser`, `YM`
                ORDER BY `s`.`advertiser`, `YM` DESC
            ) AS `sums`
			GROUP BY `advertiser`
		";
	    $averages = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);
	    //\yii\helpers\VarDumper::dump($sql, 10, true);
	    //\yii\helpers\VarDumper::dump($averages, 10, true);
	    
	    $report = [];
	    
	    foreach ($costs as $cost) {
	    	$advertiser = strtolower($cost['advertiser']);
	    	$report[$advertiser] = [
	    		'cost' => $cost['cost'],
	    		'advertiser' => $advertiser,
		    ];
	    }
	    
	    foreach ($sales as $sale) {
		    $advertiser = strtolower($sale['advertiser']);
	    	if (!isset($report[$advertiser])) $report[$advertiser] = [];
	    	$report[$advertiser] += $sale;
	    }
	    
	    foreach ($averages as $average) {
		    $advertiser = strtolower($average['advertiser']);
	    	if (!isset($report[$advertiser])) $report[$advertiser] = [];
	    	$report[$advertiser] += $average;
	    }

	    /*
            [cost] => 239.81
            [advertiser] => Apiland.ro
            [valoare_comisioane_aprobate] => 408.04
            [valoare_comisioane_asteptare] => 101.04
            [valoare_comisioane_anulate] => 33.04
            [valoare_comisioane_total] => 542.12
            [volum_comisioane_aprobate] => 13
            [volum_comisioane_asteptare] => 5
            [volum_comisioane_anulate] => 3
            [volum_comisioane_total] => 21
            [value_accepted_details] => 2018-04|673.44,2018-07|139.08,2018-03|581.04,2018-06|400.52,2018-05|915.31
            [volume_accepted_details] => 2018-04|30,2018-07|4,2018-03|22,2018-06|17,2018-05|38
            [value_rejected_details] => 2018-04|0.00,2018-07|11.80,2018-03|20.16,2018-06|21.24,2018-05|0.00
            [volume_rejected_details] => 2018-04|0,2018-07|2,2018-03|1,2018-06|1,2018-05|0
            [valoare_comisioane_aprobate_avg] => 541.878000
            [volum_comisioane_aprobate_avg] => 22.2000
            [valoare_comisioane_anulate_avg] => 10.640000
            [volum_comisioane_anulate_avg] => 0.8000
	     * */
	    
	    //final tuning
	    foreach ($report as $advertiser => $values) {
		
		    if (!isset($values['valoare_comisioane_aprobate'])) {
			    $values['valoare_comisioane_aprobate'] = '0.00';
		    }
		    if (!isset($values['valoare_comisioane_asteptare'])) {
			    $values['valoare_comisioane_asteptare'] = '0.00';
		    }
		    if (!isset($values['valoare_comisioane_anulate'])) {
			    $values['valoare_comisioane_anulate'] = '0.00';
		    }
		    if (!isset($values['valoare_comisioane_total'])) {
			    $values['valoare_comisioane_total'] = '0.00';
		    }
		    if (!isset($values['volum_comisioane_aprobate'])) {
			    $values['volum_comisioane_aprobate'] = 0;
		    }
		    if (!isset($values['volum_comisioane_asteptare'])) {
			    $values['volum_comisioane_asteptare'] = 0;
		    }
		    if (!isset($values['volum_comisioane_anulate'])) {
			    $values['volum_comisioane_anulate'] = 0;
		    }
		    if (!isset($values['volum_comisioane_total'])) {
			    $values['volum_comisioane_total'] = 0;
		    }
		    
	    	// rata aprobare
		    $ra_valoare = $ra_volum = 0;
		    if ($values['valoare_comisioane_aprobate'] > 0 || $values['valoare_comisioane_anulate'] > 0) {
			    $ra_valoare = $values['valoare_comisioane_aprobate'] / ($values['valoare_comisioane_aprobate'] + $values['valoare_comisioane_anulate']);
		    }
		
	        if ($values['volum_comisioane_aprobate'] > 0 || $values['volum_comisioane_anulate'] > 0) {
			    $ra_volum = $values['volum_comisioane_aprobate'] / ($values['volum_comisioane_aprobate'] + $values['volum_comisioane_anulate']);
		    }
		    
		    // rata aprobare medie
		    $ram_valoare = $ram_volum = 0;
		    if (isset($values['valoare_comisioane_aprobate_avg']) && isset($values['valoare_comisioane_anulate_avg'])) {
			    if ($values['valoare_comisioane_aprobate_avg'] > 0 || $values['valoare_comisioane_anulate_avg'] > 0) {
				    $ram_valoare = $values['valoare_comisioane_aprobate_avg'] / ($values['valoare_comisioane_aprobate_avg'] + $values['valoare_comisioane_anulate_avg']);
			    }
		    }
		    if (isset($values['volum_comisioane_aprobate_avg']) && isset($values['volum_comisioane_anulate_avg'])) {
			    if ($values['volum_comisioane_aprobate_avg'] > 0 || $values['volum_comisioane_anulate_avg'] > 0) {
				    $ram_volum = $values['volum_comisioane_aprobate_avg'] / ($values['volum_comisioane_aprobate_avg'] + $values['volum_comisioane_anulate_avg']);
			    }
		    }
		    
		    $cost = 0;
		    if (isset($values['cost'])) {
		    	$cost = $values['cost'];
		    }
		    
		    $values['cost'] = number_format($cost, 2, '.', '');
		    
		    $values['ra_valoare'] = number_format($ra_valoare * 100, 2, '.', '');
		    $values['ram_valoare'] = number_format($ram_valoare * 100, 2, '.', '');
		    
		    $values['ra_volum'] = number_format($ra_volum * 100, 2, '.', '');
		    $values['ram_volum'] = number_format($ram_volum * 100, 2, '.', '');
		    
		    $values['profit_garantat'] = number_format($values['valoare_comisioane_aprobate'] - $values['cost'], 2, '.', '');
			$values['profit_estimat'] = number_format($values['valoare_comisioane_aprobate'] + $values['valoare_comisioane_asteptare'] * $values['ram_valoare'] / 100 - $values['cost'], 2, '.', '');
			   
		    $report[$advertiser] = $values;
	    }
	
	    return $report;
    }
}