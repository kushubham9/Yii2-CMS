<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 26/02/17
 * Time: 11:55 AM
 *
 * @var $comment_model \common\models\Comment [];
 */

?>


<h2 class="comments-title tz-title"><span>Comments (<?= sizeof($comment_model); ?>)</span></h2>
<ol class="comments-list">
    <?php
        foreach ($comment_model as $comment):
    ?>

        <li>
            <div class="comment-image">
                <img src="images/data/author.jpg" alt="author">
            </div>
            <div class="comment-block">
                <cite><?= $comment->author_name ?></cite>
                <div class="comment-content">
                    <p>
                        <?= strip_tags($comment->content); ?>
                    </p>
                </div>
            </div>
        </li>
    <?php
        endforeach;
    ?>
</ol>
