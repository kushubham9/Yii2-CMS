<?php

use yii\db\Migration;

class m161231_141636_alter_user_table extends Migration
{
    public function up()
    {
        $this->dropForeignKey('fk_status_usermeta','usermeta');
        $this->dropColumn('usermeta','status');
        $this->dropColumn('user','status');
        $this->addColumn('user','status',$this->integer());
        $this->addForeignKey('fk_user_status','user','status','status','id','SET NULL','CASCADE');
    }

    public function down()
    {
        $this->addColumn('usermeta','status',$this->integer());
        $this->addForeignKey('fk_status_usermeta', 'usermeta', 'status','status','id','SET NULL','CASCADE');
        $this->dropForeignKey('fk_user_status','user');
        $this->dropColumn('user','status');
        $this->addColumn('user','status',$this->smallInteger()->notNull()->defaultValue(10));
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
