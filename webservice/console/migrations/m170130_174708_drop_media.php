<?php

use yii\db\Migration;

class m170130_174708_drop_media extends Migration
{
    public function up()
    {
        $this->dropTable('media');
    }

    public function down()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }


        $this->createTable('media',
            ['id'=>$this->primaryKey(),
                'media_type' => $this->string()->notNull(),
                'media_title' => $this->string(),
                'media_description' => $this->text(),
                'media_url' => $this->string()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
                'user_id' => $this->integer()], $tableOptions
        );
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
