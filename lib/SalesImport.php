<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 7/9/2018
 * Time: 2:05 AM
 */

namespace yii\sales;

use app\models\Import;
use Yii;
use yii\db\Exception;

class SalesImport
{
	public $filename;
	public $filepath;
	public $content;
	
	public $platform = '';
	
	public $messages = [];
	
	public $parsed_rows = [];
	
	public $to_import_rows = [];
	public $imported_rows = [];
	public $import_id = 0;
	
	public $to_update_rows = [];
	public $updated_rows = [];
	
	public $duplicate_rows = [];
	public $failed_rows = [];
	
	public $first_record = '0';
	public $last_record = '0';
	
	public $result;
	
	
	public function __construct($filename)
	{
		$result = [];
		$this->messages[] = 'Processing file ['.$filename.']<br><hr>';
		
        $this->filepath = \Yii::getAlias('@webroot').'/jQueryFileUpload/server/php/files/'.$filename;
		
		if (is_file($this->filepath)) {
			$this->filename = $filename;
			
			//$this->content = file_get_contents($this->filepath);
			
			$this->detect_platform();
			
			if (!empty($this->platform)) {

				$parserMethod = 'Parser'.$this->platform;
				$this->$parserMethod();
				
				$this->analyze_rows(); //populate parsed_rows, duplicate_rows, to_import_rows, to_update_rows
				$this->import_rows(); //populate imported_rows
				$this->update_rows(); //populate updated_rows
				
				$result['_platform'] = $this->platform;
				$result['_parsed'] = count($this->parsed_rows);
				$result['_imported'] = count($this->imported_rows);
				$result['_updated'] = count($this->updated_rows);
				$result['_duplicates'] = count($this->duplicate_rows);
				$result['_failed'] = count($this->failed_rows);
				$result['_stats'] = '
                    Records from <strong>'.$this->first_record.'</strong>
                    to <strong>'.$this->last_record.'</strong>.
				';
				$this->messages[] = '<hr>';
				$result['messages'] = $this->messages;
                
                $result['type'] = $result['_failed'] > 0 ? 'error' : ($result['_duplicates'] > 0 ? ($result['_updated'] > 0 ? 'info' : 'warning') : 'success');

			} else {
				$result['type'] = 'error';
				$result['messages'][] = 'File Format unknown';
			}
		} else {
			$result['type'] = 'error';
			$result['messages'][] = 'File not found: '.\Yii::getAlias('@webroot').'/jQueryFileUpload/server/php/files/'.$filename;
		}
		
		$this->result = $result;
	}
	
	
	public function detect_platform() {
		
		$allowed_formats = [
			'ID,Program' => '2Performant',
			'Advertiser' => 'ProfitShare',
		];
		
		$lines = file($this->filepath);
		foreach ($allowed_formats as $test => $platform) {
			if (preg_match('/^'.$test.'/', $lines[0]) || preg_match('/^'.$test.'/', $lines[7]) || preg_match('/^'.$test.'/', $lines[8]) || preg_match('/^'.$test.'/', $lines[9])) {
				$this->platform = $platform;
				break;
			}
		}
		
	}
	
	
	/*
	Advertiser	            in PS este coloana “Advertiser” , in 2P este coloana “Program”
	Data Clicului	        in PS este coloana “Data Ora Click” , in 2P este coloana “Click Date”
	Data Conversiei	        in PS este coloana “Data Ora Comanda” , in 2P este coloana “Transaction Date”
	Valoarea Comisionului	in PS este coloana “Valoare Comision Inregistrat” daca statusul comisionului este “In asteptare” si coloana “Valoare Comision Aprobat” daca statusul comisionului este “Aprobat”. In 2P este coloana “Commission Amount (RON)”
	Refferer	            in PS este coloana “Refferer/Cautare” , in 2P este coloana “Click Referrer”
	Status	                in PS este coloana “Status” , in 2P este coloana “Status”
	 * */
	
	public function Parser2Performant() {
		$lines = file($this->filepath);
		unset($lines[0]);
		$rows = [];
		
		foreach ($lines as $line) {
			if (empty(trim($line))) continue;
			
			// sanitize "word1, word2" columns
			$columns = str_getcsv($line);
			
			//ID,Program,Program Status,Affiliate,Commission type,Commission Amount (EUR),Commission Amount (RON),Status,Sale Amount (EUR),Sale Amount (RON),Description,Transaction Date,Transaction IP,Click Date,Click IP,Click Referrer,Click Redirect,Device Type,Click Tag,Initial Commission Amount,Comments
			
			$row = [
				'platform'          => '2Performant',
				'platform_id'       => $columns[0],
				'advertiser'        => $columns[1],
				'click_date'        => $this->utc_to_datetime($columns[13]),
				'conversion_date'   => $this->utc_to_datetime($columns[11]),
				'amount'            => $columns[6],
				'referrer'          => $columns[15],
				'original_status'   => $columns[7],
				'status'            => $this->get_status($columns[7]),
			];
			
			$rows[] = $row;
		}
		
		$this->parsed_rows = $rows;
	}
	
	public function ParserProfitShare() {
		$lines = file($this->filepath);
		array_splice($lines, 0, 9);
		$rows = [];
		
		foreach ($lines as $line) {
			if (empty(trim($line))) continue;
            
            // sanitize "word1, word2" columns
            $columns = str_getcsv($line);
            
            if ($columns[13] == 'link') {
                //v1
                //Advertiser,"Nr. Identificare Comanda","Data Ora Comanda","Data Ora Click","Data Blocare","Tip comision","Cantitate produse","Valoarea Comision Aprobat","Valoarea Comision Inregistrat","Valoare Vanzare",Status,Refferer/Cautare,"Perioada de decizie","Tip instrument","Instrument de promovare","Device Type","Device Name","Device Version","Device Brand","Device Model","Browser Name"
                
                $row = [
                    'platform'          => 'ProfitShare',
                    'platform_id'       => $columns[1],
                    'advertiser'        => $columns[0],
                    'click_date'        => $columns[3],
                    'conversion_date'   => $columns[2],
                    'amount'            => $columns[10] == 'Aprobat' ? $columns[7] : $columns[8],
                    'referrer'          => $columns[11],
                    'original_status'   => $columns[10],
                    'status'            => $this->get_status($columns[10]),
                ];
                $rows[] = $row;
            }
            
            
            if ($columns[15] == 'link') {
                //v2
                //Advertiser,"Nr. Identificare Comanda","Data Ora Comanda","Data Ora Click","Data Blocare","Last update","Tip comision","Cantitate produse","Valoarea Comision Aprobat","Valoarea Comision Asteptare","Valoarea Comision Inregistrat","Valoare Vanzare",Status,Refferer/Cautare,"Perioada de decizie","Tip instrument","Instrument de promovare","Device Type","Device Name","Device Version","Device Brand","Device Model","Browser Name"
                //Aloshop.tv,C7LP-996715510,"2017-11-06 19:18:40","2017-11-06 19:02:18","In asteptare","2017-11-13 15:14:22","Comision comanda",1,0.00,0,23.02,209.24,Anulate,http://pmark.ro/aloshop/https://aloshop.tv/sanatate-si-frumusete/vitarid-r?gclid=Cj0KCQiArYDQBRDoARIsAMR8s_QLhS2YlPjfnpZNScINjbmxMoldxp4irgEeleyEkCUzcoHZNSMK1akaAk3zEALw_wcB,"0 zile",link,AloShop.tv,desktop,Windows,7,,,Chrome
                //Aloshop.tv,C7LP-996735702,"2017-11-25 08:00:37","2017-11-24 08:21:20","2017-12-06 18:30:05","2017-12-06 18:30:05","Comision comanda",1,23.02,0,23.02,209.24,Aprobate,http://pmark.ro/aloshop/https://aloshop.tv/sanatate-si-frumusete/vitarid-r?gclid=Cj0KCQiAgNrQBRC0ARIsAE-m-1xIpPV_wWGyIZwmgdDq7sJpk2CPPn_q_1DAxlsUK1Tk0qalZhF2qcEaApfHEALw_wcB,"1 zi",link,AloShop.tv,mobile,Android,6.0,Sony,"Xperia Z2","Chrome Mobile"
    
    
                $row = [
                    'platform'          => 'ProfitShare',
                    'platform_id'       => $columns[1],
                    'advertiser'        => $columns[0],
                    'click_date'        => $columns[3],
                    'conversion_date'   => $columns[2],
                    'amount'            => $columns[12] == 'Aprobate' ? $columns[8] : ('Anulate' ? $columns[10] : $columns[9]),
                    'referrer'          => $columns[13],
	                'original_status'   => $columns[12],
	                'status'            => $this->get_status($columns[12]),
                ];
			    $rows[] = $row;
            }
            
			
		}
		
		$this->parsed_rows = $rows;
	}
	
	
	public function get_status($original_status) {
		
		$original_status = trim($original_status);
		
		$statuses = [
			'Aprobate'      => 'accepted',
			'accepted'      => 'accepted',
			'paid'          => 'accepted',
			
			'Anulate'       => 'rejected',
			'rejected'      => 'rejected',
			
			'In asteptare'  => 'pending',
			'pending'       => 'pending',
		];
		
		if (isset($statuses[$original_status])) {
			$status = $statuses[$original_status];
		} else {
			$status = 'unknown';
		}
		
		return $status;
	}
	
	
	public function analyze_rows() {
        
        foreach ($this->parsed_rows as $record) {
    
            if ($record['conversion_date'] < $this->first_record || $this->first_record == '0') {
                $this->first_record = $record['conversion_date'];
            }
            if ($record['conversion_date'] > $this->last_record) {
                $this->last_record = $record['conversion_date'];
            }
            
            $params = [
                ':platform_id' => $record['platform_id'],
            ];
            
            try {
                $sale = Yii::$app->db->createCommand('SELECT platform_id, original_status, status, amount FROM sale WHERE platform_id=:platform_id')
                    ->bindValues($params)
                    ->queryOne();
                
            } catch (Exception $e) {
                $this->messages[] = $e->getMessage();
                return false;
            }
            
            if ($sale) {
	            
            	if ($sale['original_status'] != $record['original_status']) {
		            $this->to_update_rows[] = $record;
		            $this->messages[] = $record['platform_id'].': '.$sale['status'].'('.$sale['original_status'].')|'.$sale['amount'].' -> '.$record['status'].'('.$record['original_status'].')|'.$record['amount'];
	            } else {
		            $this->duplicate_rows[] = $record;
	            }
	
            } else {
                $this->to_import_rows[] = $record;
            }
            
        }
	    
		return true;
	}
	
	
	public function import_rows() {
		
		try {
			$import = new Import();
			$import->type = 'sale';
			$import->filename = $this->filename;
			if (!$import->save()) {
				$this->messages[] = 'Import not created';
				return false;
			}
			$this->import_id = $import->id;
			
		} catch (Exception $e) {
			$this->messages[] = $e->getMessage();
			return false;
		}
		
	    foreach ($this->to_import_rows as $record) {
	        
            try {
                Yii::$app->db->createCommand()->insert('sale', [
                    'platform'          => $record['platform'],
                    'platform_id'       => $record['platform_id'],
                    'advertiser'        => $record['advertiser'],
                    'click_date'        => $record['click_date'],
                    'conversion_date'   => $record['conversion_date'],
                    'amount'            => $record['amount'],
                    'referrer'          => $record['referrer'],
                    'original_status'   => $record['original_status'],
                    'status'            => $record['status'],
                    'import_id'         => $this->import_id,
                ])->execute();
    
                $this->imported_rows[] = $record;
                
            } catch (Exception $e) {
	            $this->failed_rows[] = $record;
                $this->messages[] = $e->getMessage();
            }
            
        }
	    
		return [];
	}
	
	public function update_rows() {
		
	    foreach ($this->to_update_rows as $record) {
	        
            try {
                Yii::$app->db->createCommand()->update(
                	'sale',
	                [
	                    'amount'            => $record['amount'],
	                    'original_status'   => $record['original_status'],
	                    'status'            => $record['status'],
	                    'import_id'         => $this->import_id,
	                    'modified_at'       => date('Y-m-d H:i:s'),
                    ],
	                [
		                'platform_id'       => $record['platform_id'],
	                ]
	            )->execute();
    
                $this->updated_rows[] = $record;
                
            } catch (Exception $e) {
	            $this->failed_rows[] = $record;
                $this->messages[] = $e->getMessage();
            }
            
        }
	    
		return true;
	}
	
	public function utc_to_datetime($dateWithTimeZone) {
		$time = strtotime($dateWithTimeZone);
		$date = date("Y-m-d H:i:s", $time);
		
		return $date;
	}
}