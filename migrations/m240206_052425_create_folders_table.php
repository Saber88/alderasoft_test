<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%folders}}`.
 */
class m240206_052425_create_folders_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%folders}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%folders}}');
    }
}
