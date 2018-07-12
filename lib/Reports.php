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
}