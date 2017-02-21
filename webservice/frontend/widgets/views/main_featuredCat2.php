<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 12:17 PM
 *
 * @var $post_model [] \common\models\Post;
 * @var $post_count integer;
 * @var $title string;
 *
 */

use yii\helpers\Url;
use frontend\models\Posts;
$formatter = \Yii::$app->formatter;
?>



<!--    Box 2   -->
<div class="tz-box-one border-top2">

    <!--Title-->
    <div class="tz-title-filter">
        <h3 class="tz-title">
            <span><?= $title ?></span>
        </h3>
        <div class="tz-cat-filter">
            <span>All</span>
        </div>
    </div>
    <!--End title-->

    <!--Content-->
    <div class="row">

        <!--Box content two columns-->
        <div class="cat-box-two-columns">
            <?php for ($j = 0; $j < 2; $j++){ ?>
                <div class="col-md-6">
                    <?php
                    for ($i=0; $i<3; $i++){
                        $post = array_shift($post_model);
                        if (!$post){
                            continue;
                        }
                        if ($i){
                            $data = ($post) ? Posts::getPostInformation($post,['thumb'=>true]): false;
                        }

                        else{
                            $data = ($post) ? Posts::getPostInformation($post,['imageWidth'=>600, 'imageHeight'=>400]): false;
                        }
                    ?>
                        <!--Item thumbnail wrap-->
                        <div class="<?php echo $i ? 'item-small': 'item-thumbnail-wrap';?> ">
                            <div class="tz-thumbnail">
                                <a href="<?= $data['postUrl'] ?>">
                                    <img src="<?= $data['imageUrl'] ?>" alt="<?= $data['postTitle'] ?>">
                                </a>
                            <?php echo $i ? '</div>' : ''; ?>
                            <div class="tz-infomation">
                                <h3 class="tz-post-title"><a href="<?= $data['postUrl'] ?>"> <?= $data['postTitle'] ?> </a></h3>
                                <span class="meta">by <a href="<?= $data['authorUrl'] ?>"> <?= $data['authorName']; ?> / </a>  <?= $data['postDate'] ?> </span>
                            </div>
                        <?php echo $i ? '' : '</div>'; ?>
                        </div>
<!--                        </div>-->

                    <?php
                        }
                    ?>
                </div>
            <?php } ?>
            <!--End box content two columns-->

        </div>
        <!--End content-->

    </div>
</div>
<!--Box 2 Ends Here-->