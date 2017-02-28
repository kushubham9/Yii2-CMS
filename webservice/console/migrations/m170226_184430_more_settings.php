<?php

use yii\db\Migration;

class m170226_184430_more_settings extends Migration
{
//    public function up()
//    {
//
//    }
//
//    public function down()
//    {
//        echo "m170226_184430_more_settings cannot be reverted.\n";
//
//        return false;
//    }

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->insert('option', ['id'=>30, 'name'=>'enable_featured_widget', 'value'=>'1']);
        $this->insert('option', ['name'=>'featured_widget_category', 'value'=>'']);
        $this->insert('option', ['name'=>'featured_widget_count', 'value'=>'5']);
        $this->insert('option', ['name'=>'featured_widget_display_author', 'value'=>'1']);
        $this->insert('option', ['name'=>'featured_widget_display_date', 'value'=>'1']);
        $this->insert('option', ['name'=>'featured_widget_cat_badge_count', 'value'=>'0']);
        $this->insert('option', ['name'=>'sticky_widget_1_enable', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_1_default_title', 'value'=>'Featured Articles']);
        $this->insert('option', ['name'=>'sticky_widget_1_category', 'value'=>'']);
        $this->insert('option', ['name'=>'sticky_widget_1_count', 'value'=>'5']);
        $this->insert('option', ['name'=>'sticky_widget_1_display_author', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_1_display_date', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_2_enable', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_2_default_title', 'value'=>'Featured Articles']);
        $this->insert('option', ['name'=>'sticky_widget_2_category', 'value'=>'']);
        $this->insert('option', ['name'=>'sticky_widget_2_count', 'value'=>'6']);
        $this->insert('option', ['name'=>'sticky_widget_2_display_author', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_2_display_date', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_3_enable', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_3_default_title', 'value'=>'Featured Articles']);
        $this->insert('option', ['name'=>'sticky_widget_3_category', 'value'=>'']);
        $this->insert('option', ['name'=>'sticky_widget_3_count', 'value'=>'6']);
        $this->insert('option', ['name'=>'sticky_widget_3_display_author', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_3_display_date', 'value'=>'1']);
        $this->insert('option', ['name'=>'sticky_widget_1_display_cat_badge', 'value'=>'0']);
        $this->insert('option', ['name'=>'sticky_widget_2_display_cat_badge', 'value'=>'0']);
        $this->insert('option', ['name'=>'sticky_widget_3_display_cat_badge', 'value'=>'0']);
    }

    public function safeDown()
    {
        $this->delete('option',['>','id','29']);
    }
}
