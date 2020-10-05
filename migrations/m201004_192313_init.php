<?php

use yii\db\Migration;

/**
 * Class m201004_092313_init
 */
class m201004_192313_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*
         * Таблица пользователей и её наполнение
         */
        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull()->unique(),
            'access_token' => $this->string(32)->notNull()->unique(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->batchInsert('{{%users}}', ['username', 'auth_key', 'access_token'], [
            ['Mikey Mouse', 'jOQZWVB0BUGUnO_2BwECPKJ_fZaWynB7', 'djcsMocw3dX5hbZwIZ2RrO74gnb0FjhT'],
            ['Minnie Mouse', 'eKJTJCc5IgvKqWkSwLGD2bNcQSzE79AD', '_RzyRSNN2jD2wF3dmdpgisrDWu9pzmul'],
        ]);

        /*
         * Таблица источников лидов и её наполнение
         */
        $this->createTable('{{%sources}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ]);

        $this->insert('{{%sources}}', [
            'name' => 'Поисковая система Google',
        ]);

        /*
         * Таблица лидов
         */
        $this->createTable('{{%leads}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string()->notNull()->unique(),
            'status' => $this->tinyInteger()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('NOW()'),
        ]);

        $this->addForeignKey('{{%leads_source_id_fk}}', '{{%leads}}', 'source_id', '{{%sources}}', 'id', 'RESTRICT', 'NO ACTION');
        $this->addForeignKey('{{%leads_created_by_fk}}', '{{%leads}}', 'created_by', '{{%users}}', 'id', 'RESTRICT', 'NO ACTION');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%leads_source_id_fk}}', '{{%leads}}');
        $this->dropForeignKey('{{%leads_created_by_fk}}', '{{%leads}}');
        $this->dropTable('{{%leads}}');
        $this->dropTable('{{%sources}}');
        $this->dropTable('{{%users}}');
    }
}
