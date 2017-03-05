<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 22/02/17
 * Time: 10:05 PM
 */
?>

<!--Main Menu-->
    <ul class="">

        <?php
            foreach ($links as $linkArray):
        ?>
        <?php
            $rootLink = isset($linkArray['parent']) ? $linkArray['parent'] : [];
            $childLinks = isset($linkArray['child'])?$linkArray['child']:[];
            if (sizeof($rootLink) > 0):
        ?>
            <li>
                <a href="<?= $rootLink['href'] ?>"><?= $rootLink['title'] ?></a>

                <?php
                foreach ($childLinks as $cLink):
                ?>
                    <ul class="sub-menu">
                        <li>
                            <a href="<?= $cLink['href'] ?>"><?= $cLink['title'] ?></a>
                        </li>
                    </ul>
                <?php endforeach;?>
            </li>
        <?php
            endif;
            endforeach;
         ?>
    </ul>
<!--End Main menu-->
