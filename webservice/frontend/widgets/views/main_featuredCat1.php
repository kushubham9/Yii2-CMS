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
$formatter = \Yii::$app->formatter;

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
                    <div class="tz-thumbnail">
                        <a href="<?= Url::to(['/post/'.$post->slug]); ?>">
                            <img src="<?= $data['imageUrl'] ?>" alt="<?= $post->title ?>">
                        </a>
                    </div>
                    <div class="tz-infomation">
                        <h3 class="tz-post-title"><a href="<?= Url::to(['/post/'.$post->slug]); ?>"><?= $post->title ?></a></h3>

                        <span class="meta">
                                    <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a> / <?= $formatter->asDate($post->created_at,'long') ?>
                                </span>
                        <p> <?= substr($post->content, 0, 150) ?>...</p>
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
                    <div class="tz-thumbnail">
                        <a href="<?= Url::to(['/post/'.$post->slug]); ?>">
                            <img src="<?= $data['imageUrl'] ?>" alt="<?= $post->title ?>">
                        </a>
                    </div>
                    <div class="tz-infomation">
                        <h3 class="tz-post-title"><a href="<?= Url::to(['/post/'.$post->slug]); ?>"><?= $post->title ?></a></h3>

                        <span class="meta">
                                        <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a> / <?= $formatter->asDate($post->created_at,'long') ?>
                                    </span>
                    </div>
                </div>
                <!--End item small-->

                <?php unset($data); endforeach; ?>
        </div>
    </div>
</div>

