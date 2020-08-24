<?php 

require_once("config.php");
/**
move this block into admin.php
we only want to verify logins if trying to access the 
admin dashboard
*/
if (!isset($_SESSION["userID"]) && $_SERVER["REQUEST_URI"] != "/" . ROOT_DIR . "login.php") {
    header("Location: " . LOGIN_URL);
    die;
}

require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$bb->initUser();
//$bb->getUserProfile()->print_user();

// JS
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"));
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/core.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/editor.js"));
$bb->addHeadScript(array("src" => ASSETS_DIR . "js/imgedit.js"));

$bb->addHeadScript(array("script" => 
                         "var actionSavePost = \"" . ACTION_SAVE_POST . "\";" .
                         "var actionSaveTag = \"" . ACTION_SAVE_TAG . "\";" .
                         "var actionSaveCat = \"" . ACTION_SAVE_CAT . "\";" .
                         "var actionAddTagToPost = \"" . ACTION_ADD_TAG_TO_POST . "\";" .
                         "var actionRemoveTagFromPost = \"" . ACTION_REMOVE_TAG_FROM_POST . "\";" .
                         "var actionAddCatToPost = \"" . ACTION_ADD_CAT_TO_POST . "\";" .
                         "var actionRemoveCatFromPost = \"" . ACTION_REMOVE_CAT_FROM_POST . "\";" .
                         "var actionUploadImage = \"" . ACTION_UPLOAD_IMAGE . "\";" .
                         "var actionPostDraftStatus = \"" . ACTION_POST_DRAFT_STATUS . "\";" .
                         
                         "var homeUrl = \"" . ROOT . "\";" .
                         "var dashboardTemplateDir = \"" . ROOT . SRC_DIR . "html/dashboard/\";" . 
                         "var mode = WELCOME;"));

// CSS
$bb->addHeadStyle(array("href" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Lato"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Droid+Sans|Lato"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/font-awesome.min.css"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/core.css"));

// prepare all variables, then load the template
include(TEMPLATE_DIR . 'dashboard.html');
