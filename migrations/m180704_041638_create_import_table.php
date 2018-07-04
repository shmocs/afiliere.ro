<?php

use yii\db\Migration;
use yii\db\Schema;
/**
 * Handles the creation of table `import`.
 */
class m180704_041638_create_import_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
	    $tableOptions = null;
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
	    }
	
	    // create tables. note the specific order
	    $this->createTable('{{%import}}', [
		    'id' => Schema::TYPE_PK,
		    'filename' => Schema::TYPE_STRING . ' not null',
		    'created_at' => Schema::TYPE_DATETIME . ' not null DEFAULT CURRENT_TIMESTAMP',
	    ], $tableOptions);
	
	    // add indexes for performance optimization
	    $this->createIndex('{{%import_filename}}', '{{%import}}', 'filename', false);

    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->dropTable('import');
    }
}
