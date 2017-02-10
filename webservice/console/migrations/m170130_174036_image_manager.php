<?php

use yii\db\Migration;

/**
 * Handles the creation for table `ImageManager`.
 */
class m170130_174036_image_manager extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //ImageManager: create table
        $this->createTable('ImageManager', [
            'id' => $this->primaryKey(),
            'fileName' => $this->string(128)->notNull(),
            'fileHash' => $this->string(32)->notNull(),
            'created' => $this->datetime()->notNull(),
            'modified' => $this->datetime(),
        ]);

        //ImageManager: alter id column
        $this->alterColumn('ImageManager', 'id', 'INT(10) UNSIGNED NOT NULL AUTO_INCREMENT');

    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('ImageManager');
    }
}