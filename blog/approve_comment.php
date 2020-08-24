<?php

require_once("../config.php");
$db_server = DB_SERVER;
$db_username = DB_USERNAME;
$db_password = DB_PASSWORD;
$db_name = DB_NAME;

$conn = new PDO("mysql:host=$db_server;dbname=$db_name", $db_username, $db_password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$commentid = filter_var($_GET['commentid'], FILTER_VALIDATE_INT);
settype($commentid, 'integer');
$commentsecret = filter_var($_GET['secret'], FILTER_VALIDATE_INT);
settype($commentsecret, 'integer');

$sql = "UPDATE commentbase SET approved = 1 WHERE commentid = $commentid AND commentsecret = $commentsecret";
$stmt = $conn->prepare($sql);
$stmt->execute();

echo $sql;

exit;
