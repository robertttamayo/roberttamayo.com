<?php

session_start();

define("SITE_URL", "https://www.roberttamayo.com/blog");
define("PUBLIC_ASSETS_URL", SITE_URL . '/public/assets');
define("ASSETS_URL", SITE_URL . "/assets");
define("ASSETS_DIR", __DIR__ . "/assets");
define("HOME_DIR", __DIR__ . '/');
define("BLOG_URL", SITE_URL . '/');

define("DOMAIN_NAME", "https://www.roberttamayo.com/blog/");
define("ROOT_DIR", __DIR__ . "/roberttamayo.com/");
define("BLOG_INSTALL_DIR", "/blog/");

define("ROOT", DOMAIN_NAME . BLOG_INSTALL_DIR);
define("ADMIN_ROOT", DOMAIN_NAME);
define("LOGIN_URL", DOMAIN_NAME . "admin/login.php");
define("LOGIN_SUCCESS_URL", DOMAIN_NAME . "admin/");

define("SRC_DIR", __DIR__ . '/src/');
define("MEDIA_DIR", __DIR__ . '/media/');
define("MEDIA_URL", DOMAIN_NAME . 'media/');
define("TEMPLATE_DIR", __DIR__ . '/src/html/template/');
//define("ASSETS_DIR", ROOT . 'assets/');
//define("ASSETS_URL", ROOT . 'assets/');
define("MAIN_SITE_TEMPLATE_URL", ROOT. "mainSiteHTML.html");

define("DB_SERVER", "localhost");
// define("DB_SERVER", "mysql.freehostia.com");
define("DB_NAME", "robtam10_rcbc");
define("DB_USERNAME", "robtam10_rcbc");
define("DB_PASSWORD", "25p!K@25c0dE");

define("ADMIN", "rbot");
define("ADMIN_PASSWORD", "pika25p0L!");

define("SITE_NAME", "RedCodeBlueCode.com");

define("ACTION_SAVE_POST", "save_new_post");
define("ACTION_SAVE_TAG", "save_tag");
define("ACTION_SAVE_CAT", "save_cat");
define("ACTION_TAGS_BY_POSTID", "tags_by_postid");
define("ACTION_ADD_TAG_TO_POST", "add_tag_to_post");
define("ACTION_REMOVE_TAG_FROM_POST", "remove_tag_from_post");
define("ACTION_ADD_CAT_TO_POST", "add_cat_to_post");
define("ACTION_REMOVE_CAT_FROM_POST", "remove_cat_from_post");
define("ACTION_UPLOAD_IMAGE", "upload_image");
define("ACTION_UPLOAD_FEATURED_IMAGE", "upload_featured_image");
define("ACTION_POST_DRAFT_STATUS", "post_draft_status");
define("ACTION_POST_PERMALINK", "post_draft_status");

define("PERMALINK_STRUCTURE_CATEGORY", "category");
define("CATEGORY_URI", "category");

$blog_settings = array();
$blog_settings["permalink_structure"] = PERMALINK_STRUCTURE_CATEGORY;

// database definitions
$blogbase = "blogbase";
$userbase = "userbase";
$content = "content";
$postid = "id";

include_once('helpers.php');

if (isset($_POST["action"])) {
    // don't allow post requests unless admin user is signed in
    if (!isset($_SESSION["userID"])) {
        exit;
        return;
    }
    include(SRC_DIR . "actionHandler.php");
    handle($_POST["action"]);
}
