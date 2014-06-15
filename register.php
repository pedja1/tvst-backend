<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/14/14
 * Time: 4:52 PM
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL);
header("Content-Type: application/json; charset=utf-8");
require_once("db_utils.php");
require_once("utils.php");
require_once("mail_utility.php");
if (!isset($_POST["email"]))
{
    echo Utility::error("Email is required!", "email_not_set");
    die();
}
if (!isset($_POST["password"]))
{
    echo Utility::error("Password is required!", "password_not_set");
    die();
}
if (strlen($_POST["password"]) < 6)
{
    echo Utility::error("Password must be at least 6 characters long", "password_to_short");
    die();
}
$email = $_POST["email"];
$password = $_POST["password"];
$first_name = isset($_POST["first_name"]) ? $_POST["first_name"] : NULL;
$last_name = isset($_POST["last_name"]) ? $_POST["last_name"] : NULL;
$avatar = "images/avatars/default_avatar.png";

if (isset($_FILES['file']))
{
    $allowedExts = array("jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);
    $image_name = md5($email).md5($password)."_avatar.".$extension;
    if ((($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 2000000)
        && in_array($extension, $allowedExts))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            $warning = "Image upload failed: " . $_FILES["file"]["error"];
        }
        else
        {
            //if (file_exists("images/avatars/" . $_FILES["file"]["name"]))
            //{
            //    echo $_FILES["file"]["name"] . " already exists. ";
            //}
            //else
            //{
            $success = move_uploaded_file($_FILES["file"]["tmp_name"], "images/avatars/" . $image_name);
            if (!$success)
            {
                $warning = "Server Error: Failed to move file from tmp location";
            }
            else
            {
                $avatar = "images/avatars/" . $image_name;
            }
            //}
        }
    }
    else
    {
        echo Utility::error("File extension is not valid. Valid types are: 'jpeg, jpg and png'", "invalid_file_type");
        die();
    }
}

$result = mysqli_query($con, "SELECT * FROM `user` WHERE email = '$email' LIMIT 1");
if ($result)
{
    $row = mysqli_fetch_array($result);
    if ($row == null)
    {
        $verificationCode = Utility::random_string();
        $avatar = Utility::$URL_ROOT.$avatar;
        $stmt = mysqli_prepare($con, "INSERT INTO `user`
         SET email = ?, password = ?, first_name = ?, last_name = ?, avatar = ?, email_verification_key = ?");
        mysqli_stmt_bind_param($stmt, "ssssss", $email, $password, $first_name, $last_name, $avatar, $verificationCode);
        $insertResult = mysqli_stmt_execute($stmt);
        /*$insertResult = mysqli_query($con, "INSERT INTO `user`
         (email, password, first_name, last_name, avatar, email_verification_key)
         VALUES '$email', '$password', '$first_name', '$last_name', '$avatar', '$verificationCode'");*/
        if($insertResult)
        {
            if(MailUtility::sendVerificationEmail($email, $verificationCode))
            {
                $warning = "Verification mail not sent";
            }
            echo Utility::registration_successful(isset($warning) ? $warning : null);
        }
        else
        {
            echo Utility::error("Server error, please try again later.".mysqli_error($con), "database_error");
        }

    }
    else
    {
        echo Utility::error("Email address already exist.", "email_exist");
    }
}
else
{
    echo Utility::error("Server error, please try again later.", "database_error");
}