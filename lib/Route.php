<?php
namespace lib;

/**
 * class representing a route object , URL and its variants
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class Route {
    protected  $action;
    protected  $module;
    protected  $url;
    protected  $method;
    protected  $varsNames;
    protected  $vars = array();

    function __construct($url, $module, $action, array $varsNames,$method='get'){
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
        $this->setMethod($method);
        $this->setVarsNames($varsNames);
    }

    public function hasVars(){
        return !empty($this->varsNames);
    }

    public function match($url, $method='get'){
        if (preg_match('`^'.$this->url.'$`', $url, $matches) &&
            strtolower($method) == strtolower($this->method)){
            return $matches;
        }
        else{
            return false;
        }
    }

    public function setAction($action){
        if (is_string($action)){
            $this->action = $action;
        }
    }

    public function setModule($module){
        if (is_string($module)){
            $this->module = $module;
        }
    }

    public function setUrl($url){
        if (is_string($url)){
            $this->url = $url;
        }
    }

    public function setVarsNames(array $varsNames){
        $this->varsNames = $varsNames;
    }

    public function setVars(array $vars){
        $this->vars = $vars;
    }

    public function action(){
        return $this->action;
    }

    public function module(){
        return $this->module;
    }

    public function vars(){
        return $this->vars;
    }

    public function varsNames()	{
        return $this->varsNames;
    }

    public function method(){
        return $this->method;
    }

    public function setMethod($method){
        $this->method = $method;
    }
}

?>