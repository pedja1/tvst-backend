<?php
/**
 * Created by PhpStorm.
 * User: pedja
 * Date: 6/15/14
 * Time: 12:46 PM
 */
require_once("utils.php");

class MailUtility
{

    public static function generateVerificationLink($verificationCode)
    {
        return Utility::$URL_ROOT."verify_email.php?verification_code=".$verificationCode;
    }

    public static function sendVerificationEmail($to, $verificationCode)
    {
        $subject = 'TV Show Tracker - Account Verification';
        $message = '<html><body>Hello <b>'.$to.'</b>,<br>
        Thank you for your registration.<br>
        To verify your account please click this link:<br>
        '.MailUtility::generateVerificationLink($verificationCode).'</body></html>';
        $headers = 'From: tvst@pedjaapps.net' . "\r\n" .
            'Reply-To: tvst@pedjaapps.net' . "\r\n" .
            'MIME-Version: 1.0' . "\r\n".
            'Content-type: text/html; charset=iso-8859-1' . "\r\n".
            'Bcc: predragcokulov@gmail.com' . "\r\n".
            'X-Mailer: PHP/' . phpversion();
        return mail($to, $subject, $message, $headers);
        //TODO mail() returns false, but mail is send successfully
    }
}