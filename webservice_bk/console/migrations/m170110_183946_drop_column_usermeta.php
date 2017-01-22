<?php

use yii\db\Migration;

class m170110_183946_drop_column_usermeta extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170110_183946_drop_column_usermeta cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropColumn('usermeta','updated_at');
        $this->dropColumn('usermeta','created_at');
    }

    public function safeDown()
    {
        $this->addColumn('usermeta','updated_at','datetime');
        $this->addColumn('usermeta','created_at','datetime');
    }

}
