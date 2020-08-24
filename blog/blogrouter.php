<?php 

// PUBLIC PAGE
require_once("config.php");
require_once(SRC_DIR . "bobblog.php");

define("BLOG_HOME_PAGE", 0);
define("BLOG_CATEGORY_PAGE", 1);
define("BLOG_POST_PAGE", 2);
define("BLOG_TAG_PAGE", 3);

$blog_mode = BLOG_HOME_PAGE;

$bb = new BobBlog();

// determine landing page
$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace(BLOG_INSTALL_DIR, "", $uri);
$uri = trim($uri, "/");
$uri = explode("?", $uri)[0];
$main_content = "";
$category_uri = "";

$post_style = 2;
if (isset($_GET['style'])) {
    $post_style = filter_var($_GET['style'], FILTER_SANITIZE_NUMBER_INT);
}

if ($uri === "") {

} else {

    $parts = explode("/", $uri);
    
    if (sizeof($parts) == 1) {
        // post pages have only one part
        $blog_permalink = $uri;
        $blog_mode = BLOG_POST_PAGE;
    } else if ($parts[0] == 'post') {
        $blog_permalink = $parts[1];
        $blog_mode = BLOG_POST_PAGE;
    } else {
        $blog_mode = BLOG_CATEGORY_PAGE;
        $category_uri = $parts[0];
        $category_permalink = $parts[1];
        
        if ($category_uri != CATEGORY_URI){

        } else {

        }
    }
    
}