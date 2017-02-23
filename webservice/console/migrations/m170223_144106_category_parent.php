<?php

use yii\db\Migration;

class m170223_144106_category_parent extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170223_144106_category_parent cannot be reverted.\n";
//
//        return false;
//    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->addColumn('category','parent_category',$this->integer());
        $this->addForeignKey('fk_parent_category','category','parent_category','category','id','CASCADE','CASCADE');
        $this->addColumn('category','badge_color',$this->string());
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_parent_category','category');
        $this->dropColumn('category','parent_category');
        $this->dropColumn('category','badge_color');
    }
}
