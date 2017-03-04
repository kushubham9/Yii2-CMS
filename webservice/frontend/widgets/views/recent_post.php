<?php
/**
 * @var $post_model common\models\Post
 * @var $options []
 */
use yii\helpers\Url;
?>

<ul class=" <?=$options['containerClass'] ?>">
    <?php
        for ($i=0; $i < $options['count']; $i++ ){
            $post = $post_model[$i];
    ?>
            <?php
                $data = \frontend\models\Posts::getPostInformation($post,['thumb'=>true]);
            ?>
        <li>
            <div class="<?= $options['imageContainerClass'] ?>">
                <a href="<?= $data['postUrl'] ?>">
                    <img src="<?= $data['imageUrl'] ?>" alt="<?= $post->title ?>">
                </a>
            </div>
            <div class="<?=$options['contentContainerClass'] ?>">
                <h5><a href="<?= $data['postUrl'] ?>"><?= $post->title ?></a></h5>
                <span class="recent-meta">
                    <a class="blue-light cat_name" href="<?= $data['categoryUrl']?>"><?= $data['primeCategory']->name?></a>
<!--                    by <a href="--><?//= $data['authorUrl'] ?><!--">--><?//= $data['authorName']?><!--</a>-->
                    <?php if ($options['showDate']) {echo $data['postDate']; }?>
                </span>

            </div>
        </li>
    <?php
        }
    ?>
</ul>
