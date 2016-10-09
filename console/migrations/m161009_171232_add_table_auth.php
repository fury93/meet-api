<?php

use yii\db\Migration;

class m161009_171232_add_table_auth extends Migration
{
    public function up()
    {
        $this->createTable('auth', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

    }

    public function down()
    {
        $this->dropTable('auth');
    }
}
