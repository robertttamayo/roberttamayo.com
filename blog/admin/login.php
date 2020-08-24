<?php

require_once("../config.php");
require_once(SRC_DIR . "bobblog.php");

$bb = new BobBlog();
$loginSuccess = false;

if (isset($_POST["username"])) {
    $loginName = $_POST['username'];
    $magicWord = $_POST['password'];
    try {
        $con = new PDO("mysql:host=". DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT * FROM userbase WHERE username = '$loginName' AND magicword = '$magicWord' LIMIT 1";
        $stmt = $con->prepare($sql);
        $stmt->execute();
        
        $data = $stmt->fetchAll();
        
        if (sizeof($data) == 1) {
            if ($data[0]["active"] == 1){
                // User login successful
                $_SESSION["userID"] = $data[0]["username"];
                $_SESSION["userEmail"] = $data[0]["email"];
                $_SESSION["userType"] = $data[0]["type"];
                $_SESSION["userRole"] = $data[0]["role"];
                header("Location: " . LOGIN_SUCCESS_URL);
            } else {
                echo "<pre>Not an active user.</pre>";
            }
        } else {
            // Not Successful
            echo "<pre>Incorrect username or password.</pre>";
        }
        
    } catch (PDOException $e) {
        echo "Fail: " . $e->getMessage();
    }
}

$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"));
$bb->addHeadScript(array("src" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"));

$bb->addHeadStyle(array("href" => "https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Lato"));
$bb->addHeadStyle(array("href" => "https://fonts.googleapis.com/css?family=Droid+Sans|Lato"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/font-awesome.min.css"));
$bb->addHeadStyle(array("href" => ASSETS_DIR . "css/core.css"));

// prepare all variables, then load the template
include(TEMPLATE_DIR . 'login.html');
