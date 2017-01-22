<?php

use yii\db\Migration;

class m170118_154833_alter_category extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170118_154833_alter_category cannot be reverted.\n";
//
//        return false;
//    }


    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('category','description',$this->string());
        $this->addColumn('taxinomy','description',$this->string());
    }

    public function safeDown()
    {
        $this->dropColumn('category','description');
        $this->dropColumn('taxinomy','description');
    }

}
