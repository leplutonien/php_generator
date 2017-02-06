<?php
namespace lib;
/**
 * class to structure the response to the HTTP client
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class HTTPResponse
{
    protected $page;

    function __construct()
    {
    }

    /**
     * redirect the current page
     * @param $location
     */
    public function redirect($location)
    {
        header('Location: ' . $location);
        exit;
    }

    /**
     * allows to make redirect for 404 errors (page not found)
     * @param Application $app
     * @param null $contentFile
     * @param null $layoutFile
     */
    public function redirect404(Application $app, $contentFile = null, $layoutFile = null)
    {
        $this->page = new Page($app);
        $this->addHeader('HTTP/1.0 404 Not Found');
        if (!is_null($contentFile) && !is_null($contentFile)) {
            $this->page->setContentFile($contentFile);
            $this->page->setLayout($layoutFile);
        } else {
            $this->page->setContentFile(__DIR__ . '/../app/errors/404.php');
            $this->page->setLayout('404-layout.php');
        }
        $this->send();
    }

    /** add an HTTP header to page
     * @param $header
     */
    public function addHeader($header)
    {
        header($header);
    }

    /**
     * return yhe generated page
     **/
    public function send()
    {
        exit($this->page->getGeneratedPage());
    }

    /**set the page to generate
     * @param Page $page
     */
    public function setPage(Page $page)
    {
        $this->page = $page;
    }

    /**
     * permet d'attribuer une variable cookies
     * @param $name
     * @param string $value
     * @param int $expire
     * @param null $path
     * @param null $domain
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function setCookie($name, $value = '', $expire = 0, $path = null, $domain = null, $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }
}

?>