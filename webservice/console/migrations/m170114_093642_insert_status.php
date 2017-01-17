<?php

use yii\db\Migration;
use yii\db\Expression;

class m170114_093642_insert_status extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170114_093642_insert_status cannot be reverted.\n";
//
//        return false;
//    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->batchInsert('status',['id','name','created_at','updated_at'],[
            [3,'Published',new Expression('NOW()'),new Expression('NOW()')],
            [4,'Draft',new Expression('NOW()'),new Expression('NOW()')],
            [5,'Hidden',new Expression('NOW()'),new Expression('NOW()')]
        ]);
    }

    public function safeDown()
    {
        $this->delete('status',['id' => 4]);
        $this->delete('status',['id' => 3]);
        $this->delete('status',['id' => 5]);
    }
}
