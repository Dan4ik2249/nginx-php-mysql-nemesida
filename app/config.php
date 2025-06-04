<?php

session_start();

$db_host = "mysql";
$db_user = "root";
$db_pass = "DSAewq321#@!";
$db_name = "test_db";

$mysql = new mysqli($db_host, $db_user, $db_pass, $db_name);
$mysql->set_charset("utf8");

?>
