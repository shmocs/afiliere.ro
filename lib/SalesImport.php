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
	public $imported_rows = [];
	public $duplicate_rows = [];
	public $failed_rows = [];
	
	public $first_record = '0';
	public $last_record = '0';
	
	public $result;
	
	public function __construct($filename)
	{
		$result = [];
		$this->messages[] = 'Processing file ['.$filename.']<br>';
		
        $this->filepath = \Yii::getAlias('@webroot').'/jQueryFileUpload/server/php/files/'.$filename;
		
		if (is_file($this->filepath)) {
			$this->filename = $filename;
			
			//$this->content = file_get_contents($this->filepath);
			
			$this->detect_platform();
			
			if (!empty($this->platform)) {

				$parserMethod = 'Parser'.$this->platform;
				$this->$parserMethod();
				
				$to_import = $this->analyze_rows(); //populate parsed_rows, duplicate_rows
				$this->import_rows($to_import); //populate imported_rows
				
				$result['_platform'] = $this->platform;
				$result['_parsed'] = count($this->parsed_rows);
				$result['_imported'] = count($this->imported_rows);
				$result['_duplicates'] = count($this->duplicate_rows);
				$result['_failed'] = count($this->failed_rows);
				$result['_stats'] = '
                    Imported from <strong>'.$this->first_record.'</strong>
                    to <strong>'.$this->last_record.'</strong>.
				';
				$result['messages'] = $this->messages;
                
                $result['type'] = $result['_failed'] > 0 ? 'error' : ($result['_duplicates'] > 0 ? 'warning' : 'success');

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
				'status'            => $columns[7],
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
            
			//Advertiser,"Nr. Identificare Comanda","Data Ora Comanda","Data Ora Click","Data Blocare","Tip comision","Cantitate produse","Valoarea Comision Aprobat","Valoarea Comision Inregistrat","Valoare Vanzare",Status,Refferer/Cautare,"Perioada de decizie","Tip instrument","Instrument de promovare","Device Type","Device Name","Device Version","Device Brand","Device Model","Browser Name"
			
			$row = [
				'platform'          => 'ProfitShare',
				'platform_id'       => $columns[1],
				'advertiser'        => $columns[0],
				'click_date'        => $columns[3],
				'conversion_date'   => $columns[2],
				'amount'            => $columns[10] == 'Aprobat'? $columns[7] : $columns[8],
				'referrer'          => $columns[11],
				'status'            => $columns[10],
			];
			
			$rows[] = $row;
		}
		
		$this->parsed_rows = $rows;
	}
	
	
	public function analyze_rows() {
        
        $to_import = [];
	    
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
                $sale = Yii::$app->db->createCommand('SELECT platform_id FROM sale WHERE platform_id=:platform_id')
                    ->bindValues($params)
                    ->queryOne();
                
            } catch (Exception $e) {
                $this->messages[] = $e->getMessage();
            }
            
            if ($sale) {
                $this->duplicate_rows[] = $record;
            } else {
                $to_import[] = $record;
            }
            
        }
	    
		return $to_import;
	}
	
	
	public function import_rows($new_records) {
		
		try {
			$import = new Import();
			$import->filename = $this->filename;
			if (!$import->save()) {
				$this->messages[] = 'Import not created';
				return false;
			}
			
		} catch (Exception $e) {
			$this->messages[] = $e->getMessage();
		}
		
	    foreach ($new_records as $record) {
	        
            try {
                Yii::$app->db->createCommand()->insert('sale', [
                    'platform'          => $record['platform'],
                    'platform_id'       => $record['platform_id'],
                    'advertiser'        => $record['advertiser'],
                    'click_date'        => $record['click_date'],
                    'conversion_date'   => $record['conversion_date'],
                    'amount'            => $record['amount'],
                    'referrer'          => $record['referrer'],
                    'status'            => $record['status'],
                    'import_id'         => $import->id,
                ])->execute();
    
                $this->imported_rows[] = $record;
                
            } catch (Exception $e) {
	            $this->failed_rows[] = $record;
                $this->messages[] = $e->getMessage();
            }
            
        }
	    
		return [];
	}
	
	public function utc_to_datetime($dateWithTimeZone) {
		$time = strtotime($dateWithTimeZone);
		$date = date("Y-m-d H:i:s", $time);
		
		return $date;
	}
}