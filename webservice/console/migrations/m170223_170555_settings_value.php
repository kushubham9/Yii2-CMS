<?php

use yii\db\Migration;

class m170223_170555_settings_value extends Migration
{
    public function up()
    {
        $this->insert('option',['name'=>'frontend_address', 'value'=>'http://www.dailyhawker.com']);
        $this->insert('option',['name'=>'backend_address', 'value'=>'http://backend.dailyhawker.com']);
        $this->insert('option',['name'=>'image_base_address', 'value'=>'http://backend.dailyhawker.com']);
        $this->insert('option',['name'=>'site_title', 'value'=>'Daily Hawker']);
        $this->insert('option',['name'=>'site_tagline', 'value'=>'Custom Tagline']);
        $this->insert('option',['name'=>'site_description', 'value'=>'Custom Description']);
        $this->insert('option',['name'=>'logo_url', 'value'=>'http://iamroy.in/client/blog/dist/img/logo.png']);
        $this->insert('option',['name'=>'social_fb', 'value'=>'https://www.facebook.com/dailyhawker/']);
        $this->insert('option',['name'=>'social_instagram', 'value'=>'https://www.instagram.com/dailyhawker/']);
        $this->insert('option',['name'=>'social_google', 'value'=>'']);
        $this->insert('option',['name'=>'social_linkedin', 'value'=>'']);
        $this->insert('option',['name'=>'social_youtube', 'value'=>'']);
        $this->insert('option',['name'=>'social_twitter', 'value'=>'https://twitter.com/dailyhawker']);
    }

    public function down()
    {
        $this->truncateTable('option');
//        echo "m170223_170555_settings_value cannot be reverted.\n";
//
//        return false;
    }

//    /*
//    // Use safeUp/safeDown to run migration code within a transaction
//    public function safeUp()
//    {
//    }
//
//    public function safeDown()
//    {
//    }
//    */
}
