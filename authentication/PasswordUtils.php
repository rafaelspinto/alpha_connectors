<?php
/**
 * PasswordUtils
 * 
 * @author André Aleixo <ajaaleixo@gmail.com>
 */
namespace Connectors\Authentication;

/**
 * Password utilitary class.
 * 
 * @package AuthenticationConnector
 */
class PasswordUtils
{
    /**
     * Crypt a password.
     * 
     * @param string $password The password to crypt.
     * 
     * @return string
     */
    public static function crypt($password)
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => PASSWORD_BCRYPT_DEFAULT_COST]);
    }

    /**
     * Checks a password against a stored crypted.
     * 
     * @param string $password The password to check.
     * @param string $crypted  The stored crypted password.   
     * 
     * @return boolean
     */
    public static function isValid($password, $crypted)
    {
        return password_verify($password, $crypted);
    }
}