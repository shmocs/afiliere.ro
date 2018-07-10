w<?php
use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m171213_222417_payment
 */
class m171213_222417_stats extends Migration
{


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }
	
	    // Conversion Time,Conversion Value,Google Click Id,Conversion Name

	    // create tables. note the specific order
	    $this->createTable('{{%stats}}', [
		    'id' => Schema::TYPE_PK,
		    'day' => Schema::TYPE_DATE . ' not null',
		    'platform' => Schema::TYPE_STRING . ' not null',
		    'nr_sales' => Schema::TYPE_DATETIME . ' not null',
		    'sales_value' => Schema::TYPE_DECIMAL . ' not null',
	    ], $tableOptions);
	
	    // add indexes for performance optimization
	    $this->createIndex('{{%payment_conversion_date}}', '{{%payment}}', 'conversion_date', false);
	    $this->createIndex('{{%payment_created_at}}', '{{%payment}}', 'created_at', false);

    }

    public function down()
    {
	    $this->dropTable('{{%stats}}');
    }
}
