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
        		`s`.`advertiser`,
        		
            	(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'accepted') AS `valoare_comisioane_aprobate`,
            	(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'pending') AS `valoare_comisioane_asteptare`,
            	(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'rejected') AS `valoare_comisioane_anulate`,
           		(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser`) AS `valoare_comisioane_total`,
           		
           		(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'accepted') AS `volum_comisioane_aprobate`,
            	(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'pending') AS `volum_comisioane_asteptare`,
            	(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'rejected') AS `volum_comisioane_anulate`,
           		(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser`) AS `volum_comisioane_total`
        		
            FROM `sale` `s`
            WHERE 1
            	AND `s`.`{$date_type}` BETWEEN '{$start_date}' AND '{$end_date}'
            GROUP BY `s`.`advertiser`
        	ORDER BY `s`.`advertiser` ASC
		";
	    $sql = "
			SELECT
        		`advertiser`,
            	`valoare_comisioane_aprobate`,
            	`valoare_comisioane_asteptare`,
            	`valoare_comisioane_anulate`
            FROM (
                SELECT
                    `s`.`advertiser`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'accepted' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_aprobate`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'pending' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_asteptare`,
                    IFNULL( SUM( CASE WHEN `s2`.`status` = 'rejected' THEN `s2`.`amount` END ), 0) AS `valoare_comisioane_anulate`
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
	    \yii\helpers\VarDumper::dump($sales, 10, true);
		
	    
	    $sql = "
		SELECT
			`virt`.`advertiser`,
			AVG(`virt`.`valoare_comisioane_aprobate`) AS `valoare_comisioane_aprobate_avg`,
			AVG(`virt`.`valoare_comisioane_anulate`) AS `valoare_comisioane_anulate_avg`,
			
			AVG(`virt`.`volum_comisioane_aprobate`) AS `volum_comisioane_aprobate_avg`,
			AVG(`virt`.`volum_comisioane_anulate`) AS `volum_comisioane_anulate_avg`
		FROM (
			SELECT
        		`s`.`advertiser`,
        		
            	(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'accepted') AS `valoare_comisioane_aprobate`,
            	(SELECT SUM(`s1`.`amount`) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'rejected') AS `valoare_comisioane_anulate`,
           		
           		(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'accepted') AS `volum_comisioane_aprobate`,
            	(SELECT SUM(1) FROM `sale` `s1` WHERE `s1`.`advertiser` = `s`.`advertiser` AND `s1`.`status` = 'rejected') AS `volum_comisioane_anulate`
        		
            FROM `sale` `s`
            WHERE 1
            	AND `s`.`{$date_type}` BETWEEN NOW() - INTERVAL 4 MONTH AND NOW()
            GROUP BY `s`.`advertiser`
        	-- ORDER BY `s`.`advertiser` ASC
        ) `virt`
        GROUP BY `virt`.`advertiser`
		";
	    //$averages = Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_ASSOC);
	    //\yii\helpers\VarDumper::dump($sql, 10, true);
	    //\yii\helpers\VarDumper::dump($averages, 10, true);
        $averages = [];
	    
	    $report = [];
	    
	    foreach ($costs as $cost) {
	    	$advertiser = strtolower($cost['advertiser']);
	    	$report[$advertiser] = [
	    		'cost' => $cost['cost']
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
	
	    return $report;
    }
}