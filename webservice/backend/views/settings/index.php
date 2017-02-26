<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 3:31 PM
 */
use yii\bootstrap\Tabs;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box box-primary">

            <div class="box-body">
                <div class="page-index">
                    <?php
                    echo Tabs::widget([
                        'items' => [
                            [
                                'label' => 'General',
                                'content' => $this->render('_general',['settings'=>$settings]),
                                'active' => true
                            ],
                            [
                                'label' => 'Social',
                                'content' => $this->render('_social',['settings'=>$settings]),
                            ],

                            [
                                'label' => 'Dropdown',
                                'items' => [
                                    [
                                        'label' => 'DropdownA',
                                        'content' => 'DropdownA, Anim pariatur cliche...',
                                    ],
                                    [
                                        'label' => 'DropdownB',
                                        'content' => 'DropdownB, Anim pariatur cliche...',
                                    ],
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
