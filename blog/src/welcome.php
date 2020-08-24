<?php
require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initPosts();
$posts = $bb->getPosts();

function formatDateForWelcome($date){
    $dateObj = new DateTime($date);
    $formattedDate = $dateObj->format('m/d/Y');
    
    return $formattedDate;
}

include (SRC_DIR . 'html/dashboard/welcome.html');
