<?php

use yii\db\Migration;

class m170204_051813_ad_location extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('adlocation',[
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
            'slug' => $this->string()->unique(),
            'max_width' => $this->integer(),
            'max_height' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ], $tableOptions);
        $this->addForeignKey('fk_adlocation_status','adlocation','status','status','id','SET NULL','CASCADE');

        $this->createTable('advertisement',[
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->unique()->notNull(),
            'script' => $this->text(),
            'display_order' => $this->integer(4)->defaultValue(0),
            'location' => $this->integer(),
            'status' => $this->integer(),
            'display_mobile' => $this->integer(1)->defaultValue(1),
            'display_desktop' => $this->integer(1)->defaultValue(1),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull()
        ],$tableOptions);
        $this->addForeignKey('fk_advertisement_status','advertisement','status','status','id','SET NULL','CASCADE');
        $this->addForeignKey('fk_advertisement_location','advertisement','location','adlocation','id','SET NULL','CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_advertisement_status','advertisement');
        $this->dropForeignKey('fk_advertisement_location','advertisement');
        $this->dropForeignKey('fk_adlocation_status','adlocation');
        $this->dropTable('advertisement');
        $this->dropTable('adlocation');
    }
}
