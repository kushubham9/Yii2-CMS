<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/02/17
 * Time: 10:05 PM
 */
?>

<!--Main Menu-->
<nav class="nav-menu pull-left">
    <ul class="tz-main-menu nav-collapse">
        <?php
            foreach ($links as $key=>$value):
        ?>
            <li>
                <a href="<?= $value ?>"><?= $key?></a>
            </li>
         <?php
            endforeach;
         ?>
    </ul>
</nav>
<!--End Main menu-->
