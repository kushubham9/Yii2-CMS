<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 12:25 PM
 * @var $post_model [] \common\models\Post;
 * @var $post_count integer;
 * @var $title string;
 *
 */

use yii\helpers\Url;
use frontend\models\Posts;
$formatter = \Yii::$app->formatter;
?>

<!--            Box 3    -->
<div class="tz-box-two border-top2">
    <div class="row">

        <?php for ($i=0;$i<2;$i++){ ?>
            <!--Content right-->
            <div class="col-md-6 padding-style">
                <!--Start one cat-->
                <a class="red cat_name" href="#">DON’T MISS</a>
                <div class="one-cat">
                    <?php for($j=0; $j<3; $j++)
                    {
                        $post = array_shift($post_model);
                        if (!$post)
                        {
                            continue;
                        }
                        $data = ($post) ? Posts::getPostInformation($post,['thumb'=>true]): false;

                        if ($data):
                            ?>

                            <!--Item small two-->
                            <div class="item-small style-two">
                                <div class="tz-thumbnail text-center">
                                    <a href="<?= $data['postUrl'] ?>">
                                        <img src="<?= $data['imageUrl'];?> " alt="<?= $data['postTitle'] ?>" title="<?= $data['postTitle'] ?>" style="max-height:120px">
                                    </a>
                                </div>
                                <div class="tz-infomation">
                                    <h3 class="tz-post-title"><a href="<?= $data['postUrl']?>"><?= $data['postTitle'] ?></a></h3>

                                    <span class="meta">
                                        <?php if (Yii::$app->params['settings']['sticky_widget_3_display_author']) :?>
                                            <a href="<?= $data['authorUrl']; ?>"> <?= $data['authorName'] ?></a>
                                        <?php endif; ?>

                                            <?php if (Yii::$app->params['settings']['sticky_widget_3_display_date']) :?>
                                                <?= $data['postDate'] ?>
                                            <?php endif;?>
                                    </span>
                                    <div>
                                        <a class="cat_name" style="background-color: <?= $data['categoryColor']; ?>" href="<?= $data['categoryUrl']?>"><?= $data['primeCategory']->name?></a>
                                    </div>
                                    <p><?= $data['postContent'] ?></p>
                                </div>
                            </div>
                            <!--End Item small two-->

                            <?php
                        endif; }
                    ?>
                </div>
                <!--End one cat-->
            </div>
            <!--End content left-->

        <?php }?>

    </div>
</div>
