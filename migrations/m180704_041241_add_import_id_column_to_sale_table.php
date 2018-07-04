<?php

use yii\db\Migration;

/**
 * Handles adding import_id to table `sale`.
 */
class m180704_041241_add_import_id_column_to_sale_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sale', 'import_id', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('sale', 'import_id');
    }
}
