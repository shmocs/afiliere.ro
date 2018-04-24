<?php
use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m171213_222417_payment
 */
class m171213_222417_payment extends Migration
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
	    $this->createTable('{{%payment}}', [
		    'id' => Schema::TYPE_PK,
		    'platform' => Schema::TYPE_STRING . ' not null',
		    'conversion_date' => Schema::TYPE_DATETIME . ' not null',
		    'conversion_value' => Schema::TYPE_DECIMAL . ' not null',
		    'gclid' => Schema::TYPE_STRING . ' not null',
		    'converion_name' =>  Schema::TYPE_STRING . ' not null',
		    'created_at' => Schema::TYPE_DATETIME . ' not null',
	    ], $tableOptions);
	
	    // add indexes for performance optimization
	    $this->createIndex('{{%payment_conversion_date}}', '{{%payment}}', 'conversion_date', false);
	    $this->createIndex('{{%payment_created_at}}', '{{%payment}}', 'created_at', false);

    }

    public function down()
    {
	    $this->dropTable('{{%payment}}');
    }
}
