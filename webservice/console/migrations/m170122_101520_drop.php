<?php

use yii\db\Migration;

class m170122_101520_drop extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170122_101520_drop cannot be reverted.\n";
//
//        return false;
//    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->dropForeignKey('fk_media_usermeta','usermeta');
        $this->dropForeignKey('fk_post_media','post');
    }

    public function safeDown()
    {
        $this->addForeignKey('fk_media_usermeta', 'usermeta', 'profile_pic','media','id','SET NULL','CASCADE');
        $this->addForeignKey('fk_post_media','post','featured_image','media','id','SET NULL','CASCADE');
    }
}
