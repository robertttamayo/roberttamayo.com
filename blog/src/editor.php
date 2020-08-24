<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initUser();

$postid = "";

$postcontent = "";
$posttitle = "";
$hasContent = false;
$isDraft = false;
$permalink = "";
$featured_image = "";

if (isset($_GET["postid"])) {
    $postid = $_GET["postid"];
    
    $post = $bb->getPost($postid);
    $postcontent = $post->content;
    $posttitle = $post->title;
    $isDraft = $post->draft;
    $permalink = $post->permalink;
    $featured_image = $post->featuredimage;
    
    $hasContent = true;
} else {
    //must save this new draft
    include_once (SRC_DIR . "actionHandler.php");
    $postid = saveNewDraft();
//    $_POST["postid"] = $postid;
//    $_POST["action"] = ACTION_TAGS_BY_POSTID;
//    handle($_POST["action"]);
}

$grayFactor = .15;
function writeColorChooseSingle($_r, $_g, $_b, $grayFactor){
    $r = (int)($_r + ((128 - $_r) * $grayFactor));
    $g = (int)($_g + ((128 - $_g) * $grayFactor));
    $b = (int)($_b + ((128 - $_b) * $grayFactor));
    
    print <<<CITE
        <button class="color-choose" data-r="$r" data-g="$g" data-b="$b" style="background: rgb($r, $g, $b);">
CITE;
}
function writeColorChooseDrop($_r, $_g, $_b, $grayFactor){
    $_r = (int)($_r + ((128 - $_r) * $grayFactor));
    $_g = (int)($_g + ((128 - $_g) * $grayFactor));
    $_b = (int)($_b + ((128 - $_b) * $grayFactor));
    for ($i = 1; $i <= 3; $i++) { 
        
        $v = ($i * 0.25); 
        $r = (int)($v * $_r);
        $g = (int)($v * $_g);
        $b = (int)($v * $_b);
        print <<<CITE
        <button class="color-choose" data-r="$r" data-g="$g" data-b="$b" style="background: rgb($r, $g, $b);">
CITE;
    }
    for ($i = 1; $i <= 3; $i++) { 
    
        $v = ($i * 0.25); 
        $r = (int)($_r + (255 - $_r) * $v);
        $g = (int)($_g + (255 - $_g) * $v);
        $b = (int)($_b + (255 - $_b) * $v);

        print <<<CITE
        <button class="color-choose" data-r="$r" data-g="$g" data-b="$b" style="background: rgb($r, $g, $b);">
CITE;
    }
    
}
include (SRC_DIR . 'html/dashboard/editor.html');
