<?php
namespace app;
/**
 * Application representation
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class App extends \lib\Application{

    public function __construct(){
        parent::__construct();
    }

    /**
     * run the application
     */
    public function run(){
        $controller = $this->getController();
        $controller->execute();
        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
?>