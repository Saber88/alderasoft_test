<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%menu}}`.
 */
class m240206_053645_create_menu_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%menu}}', [
            'id' => $this->primaryKey(),
            'parent' => $this->integer()->notNull(),
            'position' => $this->integer()->notNull(),
            'folder_id' => $this->integer(),
            'page_id' => $this->integer(),
        ]);

        $this->createIndex('folder_id', '{{%menu}}', 'folder_id');
        $this->createIndex('page_id', '{{%menu}}', 'page_id');

        $this->addForeignKey('folder_id', '{{%menu}}', 'folder_id', '{{%folders}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('page_id', '{{%menu}}', 'page_id', '{{%pages}}', 'id', 'CASCADE', 'CASCADE');

        $this->insert('{{%menu}}', ['parent' => 0, 'position' => 1, 'page_id' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('folder_id', '{{%menu}}');
        $this->dropForeignKey('page_id', '{{%menu}}');

        $this->dropTable('{{%menu}}');
    }
}
