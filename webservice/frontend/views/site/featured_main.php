<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 18/02/17
 * Time: 7:30 PM
 */
use yii\helpers\Url;
use yii\bootstrap\Html;
$formatter = \Yii::$app->formatter;
?>

<!--Featured post-->
<div class="featured-post-wrap">
    <div class="container">
        <ul class="featured-post owl-carousel owl-theme" style="opacity: 1; display: block;">

            <div class="owl-wrapper-outer">
                <div class="owl-wrapper">
                    <div class="owl-item">
                        <li>

                            <?php $count = 0;
                            foreach ($featured_post_model as $post): ?>

                                <?php
                                $data = getPostInformation($post);
                                ?>
                                <!--Item zero-->
                                <div class="<?php echo 'tz-grid-post-'.$count++; ?>">
                                    <div class="tz-featured-thumb">
                                        <a href="<?= Url::to(['/post/'.$post->slug]); ?>">
                                            <?= Html::img($data['imageUrl'], ['class'=>'img-responsive','alt'=>$post->title]) ?>
                                        </a>
                                    </div>
                                    <div class="tz-featured-info">
                                        <a class="glod cat_name" href="<?= Url::to('/category/'.$data['primeCategory']->slug); ?>"> <?=$data['primeCategory']->name?> </a>
                                        <h3><a href="<?= Url::to(['/post/'.$post->slug]); ?>"> <?= $post->title; ?></a></h3>
                                        <span class="tz-featured-meta">
                                                <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a> / <?= $formatter->asDate($post->created_at,'long') ?>
                                            </span>
                                    </div>
                                </div>
                                <?php
                                unset($data);
                            endforeach;
                            ?>
<!--End Item-->

</li>

</div>
</div>
</div>
</ul>
</div>
</div>
<!--End Featured post-->