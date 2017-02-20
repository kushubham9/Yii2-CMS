<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 11:12 AM
 */

/**
 * @var $post_model \frontend\models\Posts;
 */
use frontend\models\Posts;
use yii\bootstrap\Html;
use yii\helpers\Url;
$formatter = \Yii::$app->formatter;

?>


<!--Featured post-->
<div class="featured-post-wrap">
    <div class="container">
        <ul class="featured-post owl-carousel owl-theme">

            <div class="owl-wrapper-outer">
                <div class="owl-wrapper">
                    <div class="owl-item">
                        <li>

                            <?php $count = 0;
                            for ($i=0; $i < $post_count; $i++) {
                                $post = array_shift($post_model);
                                if (!$post){
                                    continue;
                                }
                                $data = Posts::getPostInformation($post);
                                ?>

                                <div class="<?php echo 'tz-grid-post-' . $count++; ?>">
                                    <div class="tz-featured-thumb">
                                        <a href="<?= Url::to(['/post/' . $post->slug]); ?>">
                                            <?= Html::img($data['imageUrl'], ['alt' => $post->title]) ?>
                                        </a>
                                    </div>
                                    <div class="tz-featured-info">
                                        <a class="glod cat_name"
                                           href="<?= Url::to('/category/' . $data['primeCategory']->slug); ?>"> <?= $data['primeCategory']->name ?> </a>
                                        <h3>
                                            <a href="<?= Url::to(['/post/' . $post->slug]); ?>"> <?= $post->title; ?></a>
                                        </h3>
                                        <span class="tz-featured-meta">
                                                <a href="<?= Url::to(['/user/' . $post->user->username]); ?>"> <?= $data['authorName'] ?></a> / <?= $formatter->asDate($post->created_at, 'long') ?>
                                            </span>
                                    </div>
                                </div>
                            <?php
                                unset($data);
                                 }
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