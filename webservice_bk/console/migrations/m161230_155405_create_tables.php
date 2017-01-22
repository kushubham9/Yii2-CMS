<?php

use yii\db\Migration;

class m161230_155405_create_tables extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('status',
            ['id' => $this->primaryKey(),
                'name' => $this->string(25)->unique(),
                'updated_at' => $this->dateTime()->notNull(),
                'created_at' => $this->dateTime()->notNull()],$tableOptions
        );

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

        $this->addForeignKey('fk_media_user','media','user_id','user','id','SET NULL','CASCADE');

        $this->createTable('category',
            [
                'id' => $this->primaryKey(),
                'name'=> $this->string(50)->notNull(),
                'slug'=> $this->string()->notNull()->unique(),
                'created_at'=>$this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull()
            ],$tableOptions
        );

        $this->createTable('option',
            [
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->unique(),
                'value' => $this->text()
            ], $tableOptions
        );

        $this->createTable('usermeta',
            ['id' => $this->primaryKey(),
                'first_name' => $this->string(50),
                'last_name' => $this->string(50),
                'nickname' => $this->string(50),
                'gender' => $this->char(1),
                'about' => $this->text(),
                'website' => $this->string(),
                'social_fb' => $this->string(),
                'social_google' => $this->string(),
                'social_linkedin' => $this->string(),
                'updated_at' => $this->dateTime()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'user_id' => $this->integer(),
                'status' => $this->integer(),
                'profile_pic' => $this->integer()], $tableOptions
        );

        $this->addForeignKey('fk_user_usermeta', 'usermeta', 'user_id','user','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_status_usermeta', 'usermeta', 'status','status','id','SET NULL','CASCADE');
        $this->addForeignKey('fk_media_usermeta', 'usermeta', 'profile_pic','media','id','SET NULL','CASCADE');

        $this->createTable('post',
            [
                'id' => $this->primaryKey(),
                'type'=> $this->string()->notNull(),
                'title'=>$this->string()->notNull(),
                'content'=>$this->text(),
                'slug'=>$this->string()->notNull()->unique(),
                'views' => $this->integer()->notNull()->defaultValue(0),
                'comment_allowed'=>$this->smallInteger(1)->notNull()->defaultValue(1),
                'status' => $this->integer(),
                'created_at'=>$this->dateTime()->notNull(),
                'updated_at'=>$this->dateTime()->notNull(),
                'user_id'=>$this->integer()->notNull(),
                'featured_image'=>$this->integer()
            ], $tableOptions
        );
        $this->addForeignKey('fk_post_user','post','user_id','user','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_post_media','post','featured_image','media','id','SET NULL','CASCADE');
        $this->addForeignKey('fk_post_status','post','status','status','id','SET NULL','CASCADE');

        $this->createTable('post_category',
            [
                'post_id'=>$this->integer()->notNull(),
                'category_id'=>$this->integer()->notNull()
            ],$tableOptions);
        $this->addPrimaryKey('pk_post_category','post_category',['post_id','category_id']);
        $this->addForeignKey('fk_post_postCat', 'post_category','post_id','post','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_cat_postCat', 'post_category','category_id','category','id','CASCADE','CASCADE');

        $this->createTable('taxinomy',
            [
                'id' => $this->primaryKey(),
                'type' => $this->string()->notNull(),
                'value' => $this->string()->notNull(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull()
            ],$tableOptions);

        $this->createTable('post_taxinomy',
            [
                'taxinomy_id'=>$this->integer()->notNull(),
                'post_id'=>$this->integer()->notNull()
            ],$tableOptions);

        $this->addPrimaryKey('pk_post_taxinomy','post_taxinomy',['taxinomy_id','post_id']);
        $this->addForeignKey('fk_post_postTax','post_taxinomy','post_id','post','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_tax_postTax','post_taxinomy','taxinomy_id','taxinomy','id','CASCADE','CASCADE');

        $this->createTable('comment',
            [
                'id'=>$this->primaryKey(),
                'author_name'=>$this->string(100)->notNull(),
                'author_email'=>$this->string(50),
                'author_website'=>$this->string(),
                'author_IP'=>$this->string(),
                'content'=>$this->text()->notNull(),
                'common_agent' => $this->string(),
                'status' => $this->integer(),
                'user_id' => $this->integer(),
                'post_id' => $this->integer()->notNull(),
                'parent_comment' => $this->integer(),
                'created_at'=>$this->dateTime(),
                'updated_at'=>$this->dateTime()
            ],$tableOptions);
        $this->addForeignKey('fk_comment_post','comment','post_id','post','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comment_user','comment','user_id','user','id','SET NULL','CASCADE');
        $this->addForeignKey('fk_comment_parent','comment','parent_comment','comment','id','CASCADE','CASCADE');
        $this->addForeignKey('fk_comment_status','comment','status','status','id','SET NULL','CASCADE');

        $this->createTable('menu',
            [
                'id' => $this->primaryKey(),
                'title' => $this->string(),
                'url' => $this->text(),
                'menu_entity' => $this->string(20)->notNull(),
                'menu_entity_id' => $this->integer(),
                'menu_parent'=>$this->integer(),
                'display_order'=>$this->smallInteger()->notNull()->defaultValue(0),
                'created_at'=>$this->dateTime(),
                'updated_at'=>$this->dateTime()
            ],$tableOptions);

        $this->addForeignKey('fk_menu_parent','menu','menu_parent','menu','id','SET NULL','CASCADE');

    }


    public function down()
    {
        $this->dropForeignKey('fk_menu_parent','menu');
        $this->dropForeignKey('fk_post_status','post');
        $this->dropForeignKey('fk_comment_parent','comment');
        $this->dropForeignKey('fk_comment_user','comment');
        $this->dropForeignKey('fk_comment_post','comment');
        $this->dropForeignKey('fk_tax_postTax','post_taxinomy');
        $this->dropForeignKey('fk_post_postTax','post_taxinomy');
        $this->dropForeignKey('fk_cat_postCat','post_category');
        $this->dropForeignKey('fk_post_postCat','post_category');
        $this->dropForeignKey('fk_media_user','media');
        $this->dropForeignKey('fk_user_usermeta','usermeta');
        $this->dropForeignKey('fk_media_usermeta','usermeta');
        $this->dropForeignKey('fk_status_usermeta','usermeta');
        $this->dropForeignKey('fk_comment_status','comment');
        $this->dropTable('usermeta');
        $this->dropTable('post');
        $this->dropTable('post_taxinomy');
        $this->dropTable('taxinomy');
        $this->dropTable('option');
        $this->dropTable('post_category');
        $this->dropTable('category');
        $this->dropTable('menu');
        $this->dropTable('comment');
        $this->dropTable('media');
        $this->dropTable('status');
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
