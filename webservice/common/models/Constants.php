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
    const SITE_TITLE ="Daily Hawker";
    const TAGLINE = "No Taglines Provided";
    const SITE_DESCRIPTION = "This is a test description of the website.";

    const FRONTEND_ADDRESS = "http://www.dailyhawker.com";
    const BACKEND_ADDRESS = "http://backend.dailyhawker.com";
    const IMAGE_BASE_ADDRESS = "http://backend.dailyhawker.com";
    const LOGO_URL = "http://iamroy.in/client/blog/dist/img/logo.png";

    const SOCIAL_FB = "";
    const SOCIAL_GOOGLE = "";
    const SOCIAL_TWITTER = "";
    const SOCIAL_INSTAGRAM = "";
    const SOCIAL_LINKEDIN = "";

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
    const ACTIVE_AD_LOCATION_STATUS = [1];
    const ACTIVE_AD_STATUS = [1];

    const DEFAULT_CATEGORY = 1;
    const DEFAULT_USER_STATUS = 1;
    const DEFAULT_POST_STATUS = 3;
    const DEFAULT_POST_CATEGORY = 1;
    const DEFAULT_COMMENT_ENABLE = 1;
}