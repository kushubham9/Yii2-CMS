<?php
/**
 * Created by PhpStorm.
 * User: Shu
 * Date: 13/01/17
 * Time: 10:50 PM
 */

namespace common\models;


class Constants
{
    const TYPE_POST = "Post";
    const TYPE_PAGE = "Page";
    const TAXINOMY_TYPE_TAGS = "Tag";
    const POST_STATUS_LIST = [3,4,5];
    const ACTIVE_USERS_STATUS = [1];
    const DEFAULT_POST_STATUS = 3;
    const DEFAULT_POST_CATEGORY = 1;
    const DEFAULT_COMMENT_ENABLE = 1;
}