<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pages}}`.
 */
class m240206_044344_create_pages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pages}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(64)->notNull(),
            'url' => $this->string(64)->notNull(),
            'content' => $this->text(),
        ]);

        $this->insert('{{%pages}}', ['title' => 'Главная', 'url' => 'index']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%pages}}');
    }
}
