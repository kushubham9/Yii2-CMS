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
            <?php
                $data = \frontend\models\Posts::getPostInformation($post,['thumb'=>true]);
            ?>
        <li>
            <div class="<?= $imageContainerClass ?>">
                <a href="<?= $data['postUrl'] ?>">
                    <img src="<?= $data['imageUrl'] ?>" alt="<?= $post->title ?>">
                </a>
            </div>
            <div class="<?=$contentContainerClass?>">
                <h5><a href="<?= $data['postUrl'] ?>"><?= $post->title ?></a></h5>
                <span class="recent-meta">
                    <a class="blue-light cat_name" href="<?= $data['categoryUrl']?>"><?= $data['primeCategory']->name?></a>
<!--                    by <a href="--><?//= $data['authorUrl'] ?><!--">--><?//= $data['authorName']?><!--</a>-->
                    <?php if ($options['showDate']) {echo $data['postDate']; }?>
                </span>

            </div>
        </li>
    <?php
        endforeach;
    ?>
</ul>
