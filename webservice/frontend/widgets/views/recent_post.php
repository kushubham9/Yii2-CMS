<?php
/**
 * @var $post_model common\models\Post
 */
use yii\helpers\Url;
?>

<ul class="<?=$containerClass ?>">
    <?php
        foreach($post_model as $post):
    ?>
        <li>
            <div class="<?= $imageContainerClass ?>">
                <a href="<?= Url::to(['/post/'.$post->slug]); ?>">
                    <img src="<?= \Yii::$app->imagemanager->getImagePath($post->featured_image); ?>" alt="<?= $post->title ?>">
                </a>
            </div>
            <div class="<?=$contentContainerClass?>">
                <h5><a href="<?= Url::to(['/post/'.$post->slug]); ?>"><?= $post->title ?></a></h5>
                <span class="recent-meta"> by <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $post->user->username; ?> /</a> <?= \Yii::$app->formatter->asDate($post->created_at, 'long'); ?> </span>

            </div>
        </li>
    <?php
        endforeach;
    ?>
</ul>
