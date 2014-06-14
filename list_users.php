<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("db_utils.php");
$result = mysqli_query($con, "SELECT * FROM users");
//echo result;
echo createJsonFromSql($result);
