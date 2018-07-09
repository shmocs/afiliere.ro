<?php
/**
 * Created by PhpStorm.
 * User: sergiu
 * Date: 7/9/2018
 * Time: 2:05 AM
 */

namespace yii\sales;


class SalesImport
{
	public $filename;
	public $filepath;
	public $content;
	public $platform = '';
	public $parsed_rows = [];
	public $imported_rows = [];
	public $duplicate_rows = [];
	
	public $result;
	
	public function __construct($filename)
	{
		$result = [
			'type' => 'success',
			'messages' => [],
		];
		
		if (is_file(\Yii::getAlias('@webroot').'/jQueryFileUpload/server/php/files/'.$filename)) {
			$this->filename = $filename;
			$this->filepath = \Yii::getAlias('@webroot').'/jQueryFileUpload/server/php/files/'.$filename;
			//$this->content = file_get_contents($this->filepath);
			
			$this->detect_platform();
			
			if (!empty($this->platform)) {

				$parserMethod = 'Parser'.$this->platform;
				$this->$parserMethod();
				
				$to_import = $this->analyze_rows(); //populate parsed_rows, duplicate_rows
				$this->import_rows($to_import); //populate imported_rows
				
				$result['type'] = 'success';
				$result['messages'][] = 'Parsed rows: ' . count($this->parsed_rows);
				$result['messages'][] = 'Imported rows: ' . count($this->imported_rows);
				$result['messages'][] = 'Duplicate rows: ' . count($this->duplicate_rows);

			}else{
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
			
			$columns = explode(',', $line);
			//ID,Program,Program Status,Affiliate,Commission type,Commission Amount (EUR),Commission Amount (RON),Status,Sale Amount (EUR),Sale Amount (RON),Description,Transaction Date,Transaction IP,Click Date,Click IP,Click Referrer,Click Redirect,Device Type,Click Tag,Initial Commission Amount,Comments
			
			$row = [
				'platform'          => '2Performant',
				'advertiser'        => $columns[1],
				'click_date'        => $columns[13],
				'conversion_date'   => $columns[11],
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
		array_splice($lines, 0, 7);
		$rows = [];
		
		foreach ($lines as $line) {
			if (empty(trim($line))) continue;
			
			$columns = explode(',', $line);
			//Advertiser,"Nr. Identificare Comanda","Data Ora Comanda","Data Ora Click","Data Blocare","Tip comision","Cantitate produse","Valoarea Comision Aprobat","Valoarea Comision Inregistrat","Valoare Vanzare",Status,Refferer/Cautare,"Perioada de decizie","Tip instrument","Instrument de promovare","Device Type","Device Name","Device Version","Device Brand","Device Model","Browser Name"
			
			$row = [
				'platform'          => 'ProfitShare',
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
		
		return [];
	}
	
	public function import_rows($new_records) {
		
		return [];
	}
}