<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$postid = "";
if (isset($_GET["postid"])) {
    $postid = filter_var($_GET["postid"], FILTER_VALIDATE_INT);
}

$bb = new BobBlog();

$featured_image = $bb->getFeaturedImage($postid);

include (SRC_DIR . 'html/dashboard/featuredimage.html');