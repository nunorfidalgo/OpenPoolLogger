<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL & ~E_NOTICE);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$host="127.0.0.1";
$port=3306;
$socket="";
$user="openpoollogger";
$password="Password123#@!";
$dbname="openPoolLogger";
?>
