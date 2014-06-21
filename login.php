<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
header("Content-Type: application/json; charset=utf-8");
require_once("db_utils.php");
require_once("utils.php");
if(!isset($_POST["email"]))
{
    echo Utility::error("Email is required!", "email_not_set");
    die();
}
if(!isset($_POST["password"]))
{
    echo Utility::error("Password is required!", "password_not_set");
    die();
}
$email = $_POST["email"];
$password = $_POST["password"];
$result = mysqli_query($con, "SELECT id, email, verified, user_auth_key FROM `user` WHERE email = '$email' AND password = '$password' LIMIT 1");
if($result)
{
    $row = mysqli_fetch_array($result);
    if($row != null)
    {
        if($row['verified'] == 1)
        {
            //if user already has auth key, we just use that key to allow multiple logins
            if($row['user_auth_key'] == null)
            {
                $user_auth_key = Utility::random_string();
            }
            else
            {
                $user_auth_key = $row['user_auth_key'];
            }
            setcookie("user_auth_key", $user_auth_key, time()+3600*24*365*5);//5 years, more or less
            $id = $row['id'];
            mysqli_query($con, "UPDATE `user` SET user_auth_key = '$user_auth_key' WHERE id = '$id'");
            echo Utility::login_success($row);
        }
        else
        {
            echo Utility::error("Your account isn't verified yet, please check your email.", "account_not_verified");
        }
    }
    else
    {
        echo Utility::error("Invalid email or password.", "login_invalid");
    }
}
else
{
    echo Utility::error("Server error, please try again later.", "database_error");
}

