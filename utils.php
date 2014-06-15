<?php

class Utility
{
    public static $URL_ROOT = "http://www.pedjaapps.net/tvst/";

    public static function createJsonFromSql($sqlResult)
    {
        $array = array();
        while ($row = mysqli_fetch_array($sqlResult))
        {
            array_push($array, array(
                'username' => $row['username'],
                'email' => $row['email'],
                'password' => $row['password']));

        }
        return json_encode($array);
    }

    public static function error($error_message, $error_code)
    {
        $array = array('status' => -1,
            'error_message' => $error_message,
            'error_code' => $error_code);
        return json_encode($array);
    }

    public static function login_success($sqlRow)
    {
        $array = array('status' => 1,
            'id' => $sqlRow['id'],
            'email' => $sqlRow['email'],
            'password' => $sqlRow['password'],
            'avatar' => $sqlRow['avatar'],
            'first_name' => $sqlRow['first_name'],
            'last_name' => $sqlRow['last_name']);
        return json_encode($array);
    }

    public static function registration_successful($warning)
    {
        $array = array('status' => 1,
            'message' => "Registration successful. Check your email for instructions on how to verify your account.");
        if($warning != null)
        {
            array_push($array, array('warning' => $warning));
        }
        return json_encode($array);
    }

    public static function random_string()
    {
        return bin2hex(mcrypt_create_iv(22, MCRYPT_DEV_URANDOM));
    }

    public static function logout_success()
    {
        $array = array('status' => 1,
            'message' => "Logout successful");
        return json_encode($array);
    }
}
