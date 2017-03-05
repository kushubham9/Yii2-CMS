<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 18/02/17
 * Time: 7:53 PM
 */
/**
 * @var post_model
 */
$formatter = \Yii::$app->formatter;
$this->title = $post_model->title;
use frontend\models\Posts;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\widgets\RecentCommentsWidget;
use frontend\widgets\CommentFormWidget;
use common\widgets\Alert;

//$data = Posts::getPostInformation($post_model);

$this->params['breadcrumbs'][] = ['label'=>'News','url'=>['/news/search']];
//$this->params['breadcrumbs'][] = ['label'=>$data['primeCategory']->name , 'url'=>['/news/search','type'=>'category','q' => $data['primeCategory']->slug]];
$this->params['breadcrumbs'][] = ['label'=>$post_model->categories[0]->name , 'url'=>['/news/search','type'=>'category','q' => $post_model->categories[0]->slug]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="blog-post-sidebar">

    <!--Blog post-->
    <div class="blog-post">
        <div class="container">
            <div class="row">
                <div class="col-md-9 border-right">

                    <article class="single-post">
                        <?= Alert::widget() ?>
<!--                        <div class="tz-single-thumbnail text-center ">-->
<!--                            <img src="" alt="" class="">-->
<!--                        </div>-->
                        <h1 class="single-title">
                            <?= $post_model->title ?>
                        </h1>
                        <div class="addthis_inline_share_toolbox"></div>
                        <div class="post-content">
                           <?= $post_model->content; ?>
                            <div class="addthis_inline_share_toolbox"></div>
                            <div class="addthis_relatedposts_inline"></div>
                        </div>
                        <div class="sing-post-footer">
                            <div class="meta-tags pull-left">
                                <?php
                                    $tags = $post_model->taxinomies;
                                ?>
                                <strong>Tags:</strong>
                                <div class="tagcloud" style="display: inline-block">
                                    <?php
                                    foreach ($tags as $tag):
                                        ?>
                                        <a href="<?= Url::to(['/news/search','type'=>'tag','q'=>$tag->slug])?>" style="display:inline-block"><?= $tag->value; ?></a>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <div class="tz-share pull-right">
                                Share:
                                <a href="#" class="fa fa-facebook-square"></a>
                                <a href="#" class="fa fa-twitter"></a>
                                <a href="#" class="fa fa-google"></a>
                                <a href="#" class="fa fa-dribbble"></a>
                                <a href="#" class="fa fa-behance"></a>
                            </div>
                        </div>

                        <div class="tz-comment">
                            <?= RecentCommentsWidget::widget(['postId' => $post_model->id]); ?>
                            <?= CommentFormWidget::widget(['postId'=>$post_model->id]);?>
                        </div>
                    </article>
                </div>
                <div class="col-md-3 blog-sidebar">
                    <div class="widget widget_search">
                        <?= Html::beginForm(['/news/search', 'type'=>'article'], 'get'); ?>
                            <input type="text" name="q" value="" placeholder="Search Articles">
                            <i class="icon-search fa fa-search"></i>
                        <?= Html::endForm(); ?>
                    </div>


<!--                    <div class="widget widget_categories">-->
<!--                        <ul>-->
<!--                            <li>-->
<!--                                <a href="page-blog.html">All Homepages</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="page-blog.html">Categories</a>-->
<!--                            </li>-->
<!---->
<!--                            <li>-->
<!--                                <a href="about-us.html">About</a>-->
<!--                            </li>-->
<!--                            <li>-->
<!--                                <a href="contact.html">Contact</a>-->
<!--                            </li>-->
<!---->
<!--                        </ul>-->
<!--                    </div>-->

                    <div class="widget">
                        <div class="tz-title-filter">
                            <h3 class="tz-title">
                                <span>RECENTS</span>
                            </h3>
                            <div class="tz-cat-filter tz-cat-filter2">
                                <span>All <i class="fa fa-angle-down"></i></span>
                            </div>
                        </div>
                        <div class="widget-ca-box">
                            <?= \frontend\widgets\RecentPostWidget::widget(['options'=> [
                                    'count' => 5,
                                    'containerClass'=>'widget-post-box',
                                    'contentContainerClass'=>'widget_item_info',
                                    'imageContainerClass'=>'widget_thumbnail',
                                    'showDate'=>false]
                                ]); ?>
                        </div>
                    </div>

                    <div class="widget">
                        <div class="tz-title-filter">
                            <h3 class="tz-title">
                                <span>TAGS</span>
                            </h3>
                        </div>
                        <div class="tagcloud">
                            <a href="#">Student</a>
                            <a href="#">Movies</a>
                            <a href="#">Queen's Land</a>
                            <a href="#">Student</a>
                            <a href="#">How</a>
                            <a href="#">Movie</a>
                            <a href="#">Videos</a>
                            <a href="#">Queensland's</a>
                            <a href="#">Student</a>
                            <a href="#">Girlfriend</a>
                            <a href="#">How</a>
                        </div>
                    </div>
                </div>

            </div><!--End row-->
        </div><!--End container-->
    </div>
    <!--End blog post-->

</div>