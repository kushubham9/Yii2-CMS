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

$data = Posts::getPostInformation($post_model);

$this->params['breadcrumbs'][] = ['label'=>'News','url'=>['/news']];
$this->params['breadcrumbs'][] = ['label'=>$data['primeCategory']->name , 'url'=>['/news?category='.$data['primeCategory']->slug]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="blog-post-sidebar">

    <!--Blog post-->
    <div class="blog-post">
        <div class="container">
            <div class="row">
                <div class="col-md-9 border-right">
                    <article class="single-post">
                        <div class="tz-single-thumbnail text-center ">
                            <img src="<?= $data['imageUrl']; ?>" alt="<?= $post_model->title ?>" class="">
                        </div>
                        <h1 class="single-title"><?= $post_model->title ?></h1>
                        <span class="post-meta">by <a href="#"><?= $data['authorName']; ?></a> /  <?= $formatter->asDatetime($post_model->created_at,'long'); ?> </span>
                        <div class="post-content">
                           <?= $post_model->content; ?>
                        </div>
                        <div class="sing-post-footer">
                            <div class="meta-tags pull-left">
                                <?php
                                    $tags = $post_model->taxinomies;
                                ?>
                                Tags:
                                <?php
                                    foreach ($tags as $tag):
                                ?>
                                    <a href="#"><?= $tag->value; ?></a>
                                <?php
                                    endforeach;
                                ?>
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
                    </article>
                </div>
                <div class="col-md-3 blog-sidebar">
                    <div class="widget widget_search">
                        <form>
                            <input type="text" name="s" value="" placeholder="Search by words">
                            <i class="icon-search fa fa-search"></i>
                        </form>
                    </div>
                    <div class="widget widget_categories">
                        <ul>
                            <li>
                                <a href="page-blog.html">All Homepages</a>
                            </li>
                            <li>
                                <a href="page-blog.html">Categories</a>
                            </li>

                            <li>
                                <a href="about-us.html">About</a>
                            </li>
                            <li>
                                <a href="contact.html">Contact</a>
                            </li>

                        </ul>
                    </div>

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
                            <?= \frontend\widgets\RecentPostWidget::widget(['count' => 5, 'containerClass'=>'widget-post-box']) ?>
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