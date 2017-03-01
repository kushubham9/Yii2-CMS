<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 19/02/17
 * Time: 2:22 PM
 *
 * @var $model [] \frontend\models\Posts
 *
 */
use yii\widgets\LinkPager;
$this->params['breadcrumbs'][] = "News";

$this->title = Yii::$app->request->get('q') ? 'Search results: '.Yii::$app->request->get('q') : 'Latest Articles';
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1 class="single-title">
                <?php
                    $title = "Latest Articles";
                    $postTitle = '';
                    $preTitle = '';

                    if (isset($_GET['q'])){
                        $title = ucwords($_GET['q']);

                        if (Yii::$app->request->get('category'))
                            $postTitle = ' Archive';

                        else if (Yii::$app->request->get('user'))
                            $preTitle = 'User Archive ';

                        else if (Yii::$app->request->get('artile'))
                            $preTitle = 'Search Query: ';
                    }
                ?>
                <?= $preTitle.' '.$title.' '.$postTitle?>
            </h1>
        </div>
    </div>
</div>

<div class="blog-post-wrap">

    <!--Blog post for blog 3 column-->
    <div class="blog-post three-columns">
        <div class="container">
            <div class="row">
                <?php
                $i=1;
                foreach ($model as $post):
                ?>

                <?php
                    $data = \frontend\models\Posts::getPostInformation($post, ['imageWidth'=>400,'imageHeight'=>240]);
                ?>
                    <div class="col-md-4 col-sm-6">
                        <!--Item blog post-->
                        <div class="item-blog-post">
                            <div class="tz-post-thumbnail">
                                <img src="<?= $data['imageUrl']; ?>" alt="<?= $data['postTitle'] ?>">
                            </div>
                            <div class="tz-post-info">
                                <h3><a href="<?= $data['postUrl'] ?>"><?= $data['postTitle'] ?></a></h3>
                                <span class="meta">by <a href="<?= $data['authorUrl'] ?>"><?= $data['authorName'] ?> / </a>  <?= $data['postDate'] ?> </span>
                                <p><?= $data['postContent'] ?></p>
                            </div>
                        </div>
                        <!--End Item blog post-->

                    </div>
                    <?php
                        if ($i%3==0)
                        {
                            echo '<div class="clearfix"></div>';
                        }
                    ?>
                <?php
                    $i = $i + 1;
                endforeach;
                ?>
            </div><!--End row-->
        </div><!--End container-->
    </div>
    <!--End blog post-->

    <!--Pagination-->
    <nav class="tz-pagination">
        <?php
        echo LinkPager::widget([
            'pagination' => $pagination,
        ]);
        ?>

    </nav>
    <!--End pagination-->

</div>
