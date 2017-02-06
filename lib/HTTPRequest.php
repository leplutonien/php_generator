<?php
namespace lib;

/**
 * Class to recover the data sent by the client via the browser
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

class HTTPRequest
{

    function __construct()
    {
    }

    /**
     *function to retrieve a variable cookies
     * @param $key
     * @return cookie value or  null
     */
    public function cookieData($key)
    {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    /**
     * check if a variable cookie exists
     * @param $key
     * @return true ou false
     */
    public function cookieExists($key)
    {
        return isset($_COOKIE[$key]);
    }

    /**
     * to recover a $GET variable
     * @param $key
     * @return
     */
    public function getData($key)
    {
        return isset($_GET[$key]) ? $_GET[$key] : null;
    }

    /**
     *check if a variable $GET exists
     * @param $key
     * @return true ou false
     */
    public function getExists($key)
    {
        return isset($_GET[$key]);
    }

    /**
     * returns the type of HTTP request
     *
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     *to recover a $POST variable
     * @param $key
     * @return
     */
    public function postData($key)
    {
        return isset($_POST[$key]) ? $_POST[$key] : null;
    }

    /**
     *check if a variable $POST exists
     * @param $key
     * @return bool
     */
    public function postExists($key)
    {
        return isset($_POST[$key]);
    }

    /**
     * retrieve the url
     * @return string
     */
    public function requestURI()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

?>