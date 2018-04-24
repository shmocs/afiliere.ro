<?php
use yii\db\Schema;
use yii\db\Migration;
class m171212_030600_init extends Migration
{
	public function up()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		
		//  Advertiser,"Nr. Identificare Comanda","Data Ora Comanda","Data Ora Click","Data Blocare","Tip comision","Cantitate produse",
		//  "Valoarea Comision Aprobat","Valoarea Comision Inregistrat","Valoare Vanzare",Status,Refferer/Cautare,"Perioada de decizie",
		//  "Tip instrument","Instrument de promovare","Device Type","Device Name","Device Version","Device Brand","Device Model","Browser Name"
		
		//  Libris.ro,C7LP-1008089150,"2017-11-27 13:41:43","2017-11-18 01:58:21","In asteptare","Comision comanda",1,0.00,3.71,37.14,"In asteptare",http://pmark.ro/lib1/https://www.libris.ro/metro-2034-9781473204300--p857267.html?gclid=EAIaIQobChMIpLG7lOjG1wIVhD8bCh0TfgJFEAAYASAAEgJCb_D_BwE,"9 zile",link,Libris.ro,mobile,Android,6.0,Huawei,"Honor 5X","Chrome Mobile"
		
		
		
		//  ID,Program,Program Status,Affiliate,Commission type,Commission Amount (EUR),Commission Amount (RON),
		//  Status,Sale Amount (EUR),Sale Amount (RON),Description,Transaction Date,Transaction IP,Click Date,Click IP,
		//  Click Referrer,Click Redirect,Device Type,Click Tag,Initial Commission Amount,Comments
		
		//  1143446,epiesa.ro,active,Daniela Vaduva,sale,5.38,24.75,accepted,107.59,494.96,
		//  "Baterie auto ROMBAT CYCLON 12V 62AH, 510A x 1 | ULEI MOTOR CASTROL MAGNATEC DIESEL B4 10W40 5L x 3 | ULEI MOTOR CASTROL EDGE TITANIUM TURBO DIESEL 5W40 1L x 3",
		//  2017-11-01 06:57:28 UTC,89.47.217.113,2017-10-04 08:07:09 UTC,89.47.217.113,http://pmark.ro/piesa/https://www.epiesa.ro/lichid-de-parbriz/acc/?gclid=Cj0KCQjwjdLOBRCkAR,
		//  https://www.epiesa.ro/lichid-de-parbriz/acc/?utm_campaign=2Performant&utm_source=daf68ddfd&utm_medium=CPS,Desktop,epiesa,5.38,
		
		
		/*
Advertiser	in PS este coloana “Advertiser” , in 2P este coloana “Program”
Data Clicului	in PS este coloana “Data Ora Click” , in 2P este coloana “Click Date”
Data Conversiei	in PS este coloana “Data Ora Comanda” , in 2P este coloana “Program”
Valoarea Comisionului	in PS este coloana “Valoare Comision Inregistrat” daca statusul comisionului este “In asteptare” si coloana “Valoare Comision Aprobat” daca statusul comisionului este “Aprobat”. In 2P este coloana “Commission Amount (RON)”
Refferer	in PS este coloana “Refferer/Cautare” , in 2P este coloana “Click Referrer”
Status	in PS este coloana “Status” , in 2P este coloana “Status”
		 */
		
		// create tables. note the specific order
		$this->createTable('{{%sale}}', [
			'id' => Schema::TYPE_PK,
			'platform' => Schema::TYPE_STRING . ' not null',
			'advertiser' => Schema::TYPE_STRING . ' not null',
			'click_date' => Schema::TYPE_DATETIME . ' not null',
			'conversion_date' => Schema::TYPE_DATETIME . ' not null',
			'amount' =>  Schema::TYPE_DECIMAL . ' not null',
			'referrer' => Schema::TYPE_STRING . ' not null',
			'status' => Schema::TYPE_STRING . ' not null',
			'created_at' => Schema::TYPE_DATETIME . ' not null',
		], $tableOptions);

		// add indexes for performance optimization
		$this->createIndex('{{%sale_platform}}', '{{%sale}}', 'platform', false);
		$this->createIndex('{{%sale_advertiser}}', '{{%sale}}', 'advertiser', false);
		$this->createIndex('{{%sale_click_date}}', '{{%sale}}', 'click_date', false);
		$this->createIndex('{{%sale_conversion_date}}', '{{%sale}}', 'conversion_date', false);
		$this->createIndex('{{%sale_created_at}}', '{{%sale}}', 'created_at', false);

	}
	public function down()
	{
		// drop tables in reverse order (for foreign key constraints)
		$this->dropTable('{{%sale}}');
	}
}