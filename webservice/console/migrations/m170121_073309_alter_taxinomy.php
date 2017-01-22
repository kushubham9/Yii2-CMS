<?php

use yii\db\Migration;

class m170121_073309_alter_taxinomy extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170121_073309_alter_taxinomy cannot be reverted.\n";
//
//        return false;
//    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('taxinomy','slug',$this->string(255));
    }

    public function safeDown()
    {
        $this->dropColumn('taxinomy','slug');
    }
}
