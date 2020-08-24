<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$postid = "";
if (isset($_GET["postid"])) {
    $postid = filter_var($_GET["postid"], FILTER_VALIDATE_INT);
}

$bb = new BobBlog();

$categories = $bb->getAllCats();
$cat = $bb->getCat($postid);
echo "<pre>";
print_r($categories);
print_r($cat);
echo "</pre>";

for ($i = 0; $i < sizeof($categories); $i++) {
    if ($categories[$i]["catid"] == $cat["catid"]) {
        $categories[$i]["active"] = true;
        break;
    }
}

include (SRC_DIR . 'html/dashboard/categories.html');
