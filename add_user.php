<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("db_utils.php");
require_once("utils.php");
$username = $_GET["username"];
$email = $_GET["email"];
$password = $_GET["password"];

$result = mysqli_query($con, "REPLACE INTO users (username, email, password) VALUES ('$username','.$email','$password')");
if($result)
{
	$result = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
	echo createJsonFromSql($result);
}
else
{
	echo "Error adding new user: ".mysqli_error($con);
}
