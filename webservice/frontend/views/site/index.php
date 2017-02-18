<?php

/* @var $this yii\web\View */
/* @var $featured_post_model \frontend\models\Posts; */
/* @var $featured_cat_model1 \frontend\models\Posts; */
/* @var $featured_cat_model2 \frontend\models\Posts; */
/* @var $featured_cat_model3 \frontend\models\Posts; */

use yii\helpers\Url;
use yii\bootstrap\Html;

$this->title = \common\models\Constants::SITE_TITLE ." | ".\common\models\Constants::TAGLINE;
?>

<?php
    function getPostInformation($model)
    {
        //Get The First Category.
        $categories = $model->categories;
        if ($categories){
            $data['primeCategory'] = $categories[0];
        }

        // Get the author of the post.
        $author = $model->user;
        // Author Name.
        $data['authorName'] = ($author->usermeta)?$author->usermeta->first_name.' '.$author->usermeta->last_name : $author->username;

        //Get the URL of the image.
        $data['imageUrl'] = \Yii::$app->imagemanager->getImagePath($model->featured_image);

        return $data;
    }
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
                            $formatter = \Yii::$app->formatter;
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


<!--Content-->
<div class="custom-container">
    <!--Start row-->
    <div class="row border-bottom2">
        <!--Content left-->
        <div class="col-md-8 col-sm-8 style-box1 tzcontent">
            <!--Wrap element-->
            <div class="theiaStickySidebar">

            <!--    Box 1   -->
            <div class="tz-box-one">
                <!--Title-->
                <div class="tz-title-filter">
                    <h3 class="tz-title">
                        <span>TECHNOLOGY</span>
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
                            $post = array_shift($featured_cat_model1);
                            if ($post):
                        ?>
                        <?php $data = getPostInformation($post); ?>
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
                        <?php foreach ($featured_cat_model1 as $post): ?>
                            <?php
                                $data = getPostInformation($post);
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

            <!--    Box 2   -->
            <div class="tz-box-one border-top2">

                <!--Title-->
                <div class="tz-title-filter">
                    <h3 class="tz-title">
                        <span>LIFE NEWS</span>
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
                                        $post = array_shift($featured_cat_model2);
                                        $data = ($post) ? getPostInformation($post): false;
                                        if ($data):
                                ?>
                                            <!--Item thumbnail wrap-->
                                            <div class="<?php echo $i ? 'item-small': 'item-thumbnail-wrap';?> ">
                                                <div class="tz-thumbnail">
                                                    <a href="<?= Url::to(['/post/'.$post->slug]); ?>">
                                                        <img src="<?= $data['imageUrl'] ?>" alt="<?= $post->title ?>">
                                                    </a>
                                                    <?php echo $i ? '</div>' : ''; ?>
                                                    <div class="tz-infomation">
                                                        <h3 class="tz-post-title"><a href="<?= Url::to(['/post/'.$post->slug]); ?>"> <?= $post->title ?></a></h3>
                                                        <span class="meta">by <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName']; ?> / </a>  <?= $formatter->asDate($post->created_at,'long') ?></span>
                                                    </div>
                                                </div>
                                            <?php echo $i ? '' : '</div>'; ?>


                                <?php
                                    endif;
                                    }
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <!--End box content two columns-->

                </div>
                    <!--End content-->

                </div>
            </div>

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
                                        $post = array_shift($featured_cat_model3);
                                        $data = ($post) ? getPostInformation($post): false;

                                        if ($data):
                                ?>

                                <!--Item small two-->
                                <div class="item-small style-two">
                                    <div class="tz-thumbnail">
                                        <a href="<?=Url::to(['/post/'.$post->slug]);?>">
                                            <img src="<?= $data['imageUrl'];?> " alt="<?= $post->title; ?>">
                                        </a>
                                    </div>
                                    <div class="tz-infomation">
                                        <h3 class="tz-post-title"><a href="<?=Url::to(['/post/'.$post->slug]);?>"><?= $post->title; ?></a></h3>
                                        <span class="tz-featured-meta">
                                            <a href="<?= Url::to(['/user/'.$post->user->username]); ?>"> <?= $data['authorName'] ?></a> / <?= $formatter->asDate($post->created_at,'long') ?>
                                        </span>
                                        <p><?= substr($post->content,0,150); ?></p>
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
        </div>

        <div class="col-md-4">

        </div>
    </div>
</div>