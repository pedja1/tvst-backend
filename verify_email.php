<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/15/14
 * Time: 2:49 PM
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);
require_once("db_utils.php");
require_once("utils.php");
if (!isset($_GET["verification_code"]))
{
    echo "Verification Code not specified.";
    die();
}
$verificationCode = $_GET["verification_code"];

$result = mysqli_query($con, "SELECT * FROM `user` WHERE email_verification_key = '$verificationCode' LIMIT 1");
if ($result)
{
    $row = mysqli_fetch_array($result);
    if ($row != null)
    {
        if($row['verified'] == 1)
        {
            echo "Your account is already verified";
        }
        else
        {
            $insertResult = mysqli_query($con, "UPDATE `user` SET verified = 1 WHERE email_verification_key = '$verificationCode'");
            if($insertResult)
            {
                echo "Account verification successful";
            }
            else
            {
                echo "Server error, please try again later: ".mysqli_error($con);
            }
        }
    }
    else
    {
        echo "Verification key not found, please request new key!";
    }
}
else
{
    echo "Server error, please try again later: ".mysqli_error($con);
}