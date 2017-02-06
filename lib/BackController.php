<?php
namespace lib;

/**
 * abstract class for handling controllers
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
abstract class BackController
{
    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';
    protected $app;

    public function __construct(Application $app, $module, $action)
    {
        $this->app = $app;
        $this->page = new Page($app);
        $this->setModule($module);
        $this->setAction($action);
        $this->setView($action);
    }

    public function setModule($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('The module must be a string value');
        }
        $this->module = $module;
    }

    public function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('The module must be a string');
        }
        $this->action = $action;
    }

    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException('The module must be a string value');
        }
        $this->view = $view;
        $this->page->setContentFile(__DIR__ . '/../app/modules/' . $this->module . '/views/' . $this->view . '.php');
    }

    /**
     * execute the function of the controller linked to the action specified in the url
     */
    public function execute()
    {
        $method = 'execute' . ucfirst(BoxFunctions::getCustomizeName($this->action));
        if (!is_callable(array($this, $method))) {
            throw new \RuntimeException('L\'action "' . $this->action . 'is not define in your module');
        }
        $this->$method();
    }

    public function page()
    {
        return $this->page;
    }

}

?>
