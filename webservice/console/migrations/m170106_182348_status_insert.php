<?php

use yii\db\Migration;
use yii\db\Expression;

class m170106_182348_status_insert extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170106_182348_status_insert cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->batchInsert('status',['id','name','created_at','updated_at'],[
            [1,'Active',new Expression('NOW()'),new Expression('NOW()')],
            [2,'InActive',new Expression('NOW()'),new Expression('NOW()')]
        ]);
    }

    public function safeDown()
    {
        $this->delete('status',['name' => 'Active']);
        $this->delete('status',['name' => 'InActive']);
    }

}
