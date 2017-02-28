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
                                $data = $post ? Posts::getPostInformation($post) : false;
                                if (!$data){
                                    continue;
                                }
                                ?>

                                <div class="<?php echo 'tz-grid-post-' . $count++; ?>">
                                    <div class="tz-featured-thumb">
                                        <a href="<?=  $data['postUrl'] ?>">
                                            <?= Html::img($data['imageUrl'], ['alt' => $post->title]) ?>
                                        </a>
                                    </div>
                                    <div class="tz-featured-info">
                                        <a class="cat_name" style="background-color: <?= $data['categoryColor']; ?>"
                                           href="<?= $data['categoryUrl'] ?>"> <?= $data['primeCategory']->name ?> </a>
                                        <h3>
                                            <a href="<?= $data['postUrl'] ?>"> <?= $data['postTitle'] ?></a>
                                        </h3>
                                        <span class="meta">
                                            <?php if (Yii::$app->params['settings']['featured_widget_display_author']) :?>
                                                <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a>
                                            <?php endif; ?>

                                            <?php if (Yii::$app->params['settings']['featured_widget_display_date']) :?>
                                                <i><?= $data['postDate'] ?></i>
                                            <?php endif;?>
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