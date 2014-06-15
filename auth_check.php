<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/13/14
 * Time: 1:16 PM
 */
//TODO this is for testing only
require_once('db_utils.php');
require_once("utils.php");
if(isset($_COOKIE['user_auth_key']))
{
    $user_auth_key = $_COOKIE['user_auth_key'];
    $result = mysqli_query($con, "SELECT email FROM `user` WHERE user_auth_key = '$user_auth_key'");
    if($result)
    {
        $row = mysqli_fetch_array($result);
        if($row != null)
        {
            echo "user is authenticated".$row['email'];
        }
        else
        {
            echo Utility::error("User not authorized(db)", "user_not_authorized");
        }
    }
    else
    {
        echo Utility::error("Server error, please try again later.", "database_error");
    }
}
else
{
    echo Utility::error("User not authorized(cookie)", "user_not_authorized");
}