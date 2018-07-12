<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 7/9/2018
 * Time: 2:05 AM
 */

namespace yii\costs;

use app\models\Import;
use Yii;
use yii\db\Exception;

class CostsImport
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
			'Cont,Ziua,Campanie,Clicuri,Cost' => 'AdWords',
		];
		
		$lines = file($this->filepath);
		foreach ($allowed_formats as $test => $platform) {
			if (preg_match('/^'.$test.'/', $lines[4]) || preg_match('/^'.$test.'/', $lines[5]) || preg_match('/^'.$test.'/', $lines[6])) {
				$this->platform = $platform;
				break;
			}
		}
		
	}
	
	
	/*
		Nume,Raport mngrpmark
		Tip,Campanie
		Frecvență,O dată
		Interval de date,Luna trecută
		Date,1 iun. 2018-30 iun. 2018
		Cont,Ziua,Campanie,Clicuri,Cost
		Afiliere 1,2018-06-02,AloShop.tv__D,32,"4,71"
		Afiliere 1,2018-06-06,AloShop.tv__D,3,"0,35"
		Afiliere 1,2018-06-17,AloShop.tv__D,6,"1,11"
	 * */
	
	public function ParserAdWords() {
		$lines = file($this->filepath);
		$rows = [];
		
		foreach ($lines as $line) {
			if (empty(trim($line))) continue;
			
			// sanitize "word1, word2" columns
			$columns = str_getcsv($line);
			
			if (!isset($columns[2])) continue;
			
			$parts = explode('__', $columns[2]);
			if (isset($parts[1])) {
				$advertiser = strtolower($parts[0]);
			} else {
				continue;
			}
			
			//Cont,Ziua,Campanie,Clicuri,Cost
			//Afiliere 1,2018-06-02,AloShop.tv__D,32,"4,71"
			
			$row = [
				'campaign_date'     => $columns[1],
				'campaign_name'     => $columns[2],
				'advertiser'        => $advertiser,
				'clicks'            => $columns[3],
				'cost'              => str_replace(',', '.', $columns[4]) * 1,
			];
			
			$rows[] = $row;
		}
		
		$this->parsed_rows = $rows;
	}
	
	
	public function analyze_rows() {
        
        foreach ($this->parsed_rows as $record) {
    
            if ($record['campaign_date'] < $this->first_record || $this->first_record == '0') {
                $this->first_record = $record['campaign_date'];
            }
            if ($record['campaign_date'] > $this->last_record) {
                $this->last_record = $record['campaign_date'];
            }
            
            $params = [
                ':campaign_date' => $record['campaign_date'],
                ':campaign_name' => $record['campaign_name'],
            ];
            
            try {
                $cost = Yii::$app->db->createCommand('SELECT * FROM cost WHERE campaign_date=:campaign_date AND campaign_name=:campaign_name')
                    ->bindValues($params)
                    ->queryOne();
                
            } catch (Exception $e) {
                $this->messages[] = $e->getMessage();
                return false;
            }
            
            if ($cost) {
	            
            	if ($cost['clicks'] != $record['clicks'] || $cost['cost'] != $record['cost']) {
		            $this->to_update_rows[] = $record;
		            $this->messages[] = $record['campaign_date'].'|'.$record['campaign_name'].': '.$cost['clicks'].'|'.$cost['cost'].' -> '.$record['clicks'].'|'.$record['cost'];
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
			$import->type = 'cost';
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
                Yii::$app->db->createCommand()->insert('cost', [
                    'campaign_date'     => $record['campaign_date'],
                    'campaign_name'     => $record['campaign_name'],
                    'advertiser'        => $record['advertiser'],
                    'clicks'            => $record['clicks'],
                    'cost'              => $record['cost'],
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
                	'cost',
	                [
	                    'clicks'        => $record['clicks'],
	                    'cost'          => $record['cost'],
	                    'import_id'     => $this->import_id,
	                    'modified_at'   => date('Y-m-d H:i:s'),
                    ],
	                [
		                'campaign_date' => $record['campaign_date'],
		                'campaign_name' => $record['campaign_name'],
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
	
}