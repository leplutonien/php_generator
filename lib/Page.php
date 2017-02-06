<?php

namespace lib;

/**
 * Description of Page
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class Page
{
    protected $contentFile;
    protected $vars = array();
    protected $layout;
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || empty($var)) {
            throw new \InvalidArgumentException('The variable name must be a null string');
        }
        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()
    {
        if (!file_exists($this->contentFile)) {
            throw new \InvalidArgumentException('The specified view is not specified');
        }
        $user = $this->app->user();
        $httpRequest = $this->app->httpRequest();
        extract($this->vars);
        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();
        ob_start();
        if (empty($this->layout))
            $this->layout = "default-layout.php";
        require __DIR__ . '/../app/templates/' . $this->layout;
        return ob_get_clean();
    }


    /**
     * assign an template (Layout) to the page
     * @param $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function setContentFile($contentFile)
    {

        if (!is_string($contentFile) || empty($contentFile)) {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }
        $this->contentFile = $contentFile;
    }
}

?>