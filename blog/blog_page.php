<?php 

require_once("config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initUser();
$bb->getUserProfile()->print_user();

