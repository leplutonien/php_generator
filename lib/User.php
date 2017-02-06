<?php
namespace lib;

/**
 * class storing all information on session
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

session_start();

class User
{

    public function __construct()
    {

    }

    public function getAttribute($attr)
    {
        return isset($_SESSION[$attr]) ? $_SESSION[$attr] : null;
    }

    public function hasFlash()
    {
        return isset($_SESSION['flash']);
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION[$attr] = $value;
    }

    public function removeAttribute($attr)
    {
        unset($_SESSION[$attr]);
    }

    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            throw new \InvalidArgumentException('Expected boolean');
        }
        $_SESSION['auth'] = $authenticated;
    }

    /**
     * @param $message
     * @param string $type
     */
    public function setFlash($message, $type)
    {
        $_SESSION['flash'] = array(
            'message' => $message,
            'type' => $type);
    }

    public function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}

?>
