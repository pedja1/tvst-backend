<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/13/14
 * Time: 12:44 PM
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("utils.php");
require_once("db_utils.php");
if(isset($_COOKIE['user_auth_key']))
{
    $user_auth_key = $_COOKIE['user_auth_key'];
    $result = mysqli_query($con, "UPDATE `user` set user_auth_key = NULL WHERE user_auth_key = '$user_auth_key'");
}
setcookie('user_auth_key', '', time()-3600*24*365);
echo logout_success();


