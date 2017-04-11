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
    const PAGE_STATUS_LIST = [3,4,5];
    const COMMENTS_STATUS_LIST = [3,4,5];
    const USER_STATUS_LIST = [1,2];
    const AD_LOCATION_STATUS_LIST = [1,2];
    const AD_STATUS_LIST = [1,2];

    const ACTIVE_USERS_STATUS = [1];
    const ACTIVE_COMMENT_STATUS = [3];
    const ACTIVE_AD_LOCATION_STATUS = [1];
    const ACTIVE_AD_STATUS = [1];

    const DEFAULT_CATEGORY = 1;
    const DEFAULT_USER_STATUS = 1;
    const DEFAULT_POST_STATUS = 3;
    const DEFAULT_POST_CATEGORY = 1;
    const DEFAULT_COMMENT_ENABLE = 1;
    const DEFAULT_COMMENT_STATUS = 5;

    const MAX_IMAGE_WIDTH = 660;
    const MAX_IMAGE_HEIGHT = 440;
    const THUMB_HEIGHT = 120;
    const THUMB_WIDTH = 180;

    const ENABLE_CACHE = true;
    const CACHE_KEY_RECENT_POST = 'recent_posts';

    const STATUS_HIDDEN = 5;
    const STATUS_PUBLISHED = 3;
    const DEFAULT_CACHE_TIME = 36000;
}