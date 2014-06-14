<?php
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
$email = $_POST["email"];
$password = $_POST["password"];
$result = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email' AND password = '$password' LIMIT 1");
if($result)
{
    $row = mysqli_fetch_array($result);
    if($row != null)
    {
        //if user already has auth key, we just use that key to allow multiple logins
        if($row['user_auth_key'] == null)
        {
            $user_auth_key = random_string();
        }
        else
        {
            $user_auth_key = $row['user_auth_key'];
        }
        header("Content-Type: application/json; charset=utf-8");
        setcookie("user_auth_key", $user_auth_key, time()+3600*24*365*5);//5 years, more or less
        $id = $row['id'];
        mysqli_query($con, "UPDATE `user` set user_auth_key = '$user_auth_key' WHERE id = '$id'");
        echo login_success($row);
    }
    else
    {
        echo error("Invalid email or password.", "login_invalid");
    }
}
else
{
    echo error("Server error, please try again later.", "database_error");
}

