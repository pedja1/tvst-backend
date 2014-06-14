<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/14/14
 * Time: 4:52 PM
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("utils.php");
require_once("db_utils.php");
if(!isset($_POST["email"]))
{
    echo error("Email is required!", "email_not_set");
    die();
}
if(!isset($_POST["password"]))
{
    echo error("Password is required!", "password_not_set");
    die();
}
if(strlen($_POST["password"]) < 6)
{
    echo error("Password must be at least 6 characters long", "password_to_short");
    die();
}
$email = $_POST["email"];
$password = $_POST["password"];
$result = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email' LIMIT 1");
if($result)
{
    $row = mysqli_fetch_array($result);
    if($row == null)
    {
        $insertResult = mysqli_query($con, "INSERT INTO `user` (email, password, first_name, last_name, avatar) VALUES '$email'");
    }
    else
    {
        echo error("Email address already exist.", "email_exist");
    }
}
else
{
    echo error("Server error, please try again later.", "database_error");
}