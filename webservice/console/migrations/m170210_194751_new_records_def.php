<?php

use yii\db\Migration;
use yii\db\Expression;

class m170210_194751_new_records_def extends Migration
{

    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
        $this->insert('category',['name'=>'Uncategorized', 'slug'=>'uncategorized', 'description'=>'Default Category', 'created_at'=> new Expression('NOW()'),
                                        'updated_at'=> new Expression('NOW()')]);
        $this->insert('user',['username'=>'Administrator', 'password_hash'=>'$2y$13$6yoLjvVORp/7EO1u8phYTuWYzhMSM4LVVsebZgcqEKj/EQLvo5nJK',
                                'email'=>'animesh@automitra.com',
                                'created_at'=> new Expression('NOW()'),
                                'updated_at'=> new Expression('NOW()'),
                                'status'=>1
                            ]
                    );
    }

    public function safeDown()
    {
        $this->delete('category',['slug'=>'uncategorized']);
        $this->delete('user',['username'=>'Administrator']);
    }

}
