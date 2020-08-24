<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$postid = "";
if (isset($_GET["postid"])) {
    $postid = filter_var($_GET["postid"], FILTER_VALIDATE_INT);
}

$bb = new BobBlog();

$all_tags = $bb->getAllTags();
$tags = $bb->getTags($postid);

for ($i = 0; $i < sizeof($all_tags); $i++) {
    for ($j = 0; $j < sizeof($tags); $j++) {
        if ($tags[$j]["id"] == $all_tags[$i]["id"]) {
            $all_tags[$i]["active"] = true;
        }
    }
}

include (SRC_DIR . 'html/dashboard/tags.html');
