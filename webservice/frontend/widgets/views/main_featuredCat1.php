<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 11:56 AM
 *
 * @var $post_model [] \common\models\Post;
 * @var $post_count integer;
 * @var $title string;
 *
 */

use yii\helpers\Url;
use frontend\models\Posts;

?>
<!--    Box 1   -->
<div class="tz-box-one">
    <!--Title-->
    <div class="tz-title-filter">
        <h3 class="tz-title">
            <span><?=$title?></span>
        </h3>
        <div class="tz-cat-filter">
            <span>All</span>
        </div>
    </div>
    <!--End title-->

    <!--Content-->
    <div class="row">
        <div class="col-md-6">
            <?php
            $post = array_shift($post_model);
            if ($post):
                ?>
                <?php $data = Posts::getPostInformation($post); ?>
                <!--Item large-->
                <div class="item-large">
                    <div class="tz-thumbnail text-center">
                        <a href="<?= $data['postUrl']; ?>">
                            <img src="<?= $data['imageUrl'] ?>" alt="<?= $data['postTitle'] ?>" title="<?= $data['postTitle'] ?>">
                        </a>
                    </div>
                    <div class="tz-infomation">
                        <a class="cat_name" style="background-color: <?= $data['categoryColor']; ?>" href="<?= $data['categoryUrl']?>"><?= $data['primeCategory']->name?></a>
                        <h3 class="tz-post-title"><a href="<?= $data['postUrl'] ?>"><?= $data['postTitle'] ?> </a></h3>

                        <span class="meta">
                            <?php if (Yii::$app->params['settings']['sticky_widget_1_display_author']) :?>
                                <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a>
                            <?php endif; ?>

                            <?php if (Yii::$app->params['settings']['sticky_widget_1_display_date']) :?>
                                <?= $data['postDate'] ?>
                            <?php endif;?>
                        </span>

                        <p> <?= $data['postContent'] ?></p>
                    </div>
                </div>
            <?php endif; ?>
            <!--End item large-->
        </div>

        <div class="col-md-6">
            <?php foreach ($post_model as $post): ?>
                <?php
                $data = Posts::getPostInformation($post);
                ?>
                <!--Item small-->
                <div class="item-small">
                    <div class="tz-thumbnail text-center">
                        <a href="<?= $data['postUrl']; ?>">
                            <img style="max-height:85px" src="<?= $data['imageUrl'] ?>" alt="<?= $data['postTitle'] ?>" title="<?= $data['postTitle'] ?>">
                        </a>
                    </div>
                    <div class="tz-infomation">
                        <h3 class="tz-post-title"><a href="<?= $data['postUrl']; ?>"><?= $post->title ?></a></h3>
                        <span class="meta">
                            <?php if (Yii::$app->params['settings']['sticky_widget_1_display_author']) :?>
                                <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a>
                            <?php endif; ?>

                            <?php if (Yii::$app->params['settings']['sticky_widget_1_display_date']) :?>
                                <i><?= $data['postDate'] ?></i>
                            <?php endif;?>
                        </span>
                        <?php if (Yii::$app->params['settings']['sticky_widget_1_display_cat_badge']) :?>
                            <div>
                                <a class="cat_name" style="background-color: <?= $data['categoryColor']; ?>" href="<?= $data['categoryUrl']?>"><?= $data['primeCategory']->name?></a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <!--End item small-->

                <?php unset($data); endforeach; ?>
        </div>
    </div>
</div>

