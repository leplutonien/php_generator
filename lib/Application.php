<?php
namespace lib;

/**
 * abstract class for managing the application
 *@author PhpGenerator (https://github.com/leplutonien/php_generator)
 */

abstract class Application{
    protected $httpRequest;
    protected $httpResponse;
    protected $user;

    function __construct(){
        $this->httpRequest = new HTTPRequest();
        $this->httpResponse = new HTTPResponse();
        $this->user = new User();
    }

    public function getController(){
        $router = new \lib\Router;
        $json = new JsonReader(__DIR__.'/../app/config/routes.json');
        $routes = $json->getAttributes();

        foreach ($routes as $route){
            $vars = array();
            //checked if the variables are present in the URL.
            if (isset($route['vars']) && count($route['vars']) >0){
                $vars = $route['vars'];
            }
            // added the route to the router.
            if(empty($route['method']))
                $router->addRoute(new Route($route['url'],$route['module'], $route['action'],$vars));
            else
                $router->addRoute(new Route($route['url'],$route['module'], $route['action'],$vars,$route['method']));
        }

        try{
            // the corresponding route is recovered to the URL
            $url=$this->httpRequest->requestURI();
            $matchedRoute = $router->getRoute($url,$this->httpRequest->method());
        }
        catch (\RuntimeException $e){
            if ($e->getCode() == \lib\Router::NO_ROUTE){
                // If no route is, is that the requested page does not exist.
                $this->httpResponse->redirect404($this);
            }
        }
        // On ajoute les variables de l'URL au tableau $_GET.
        $_GET = array_merge($_GET, $matchedRoute->vars());
        // On instancie le contrôleur.
        $controllerClass = 'app\modules\\'.$matchedRoute->module().'\\'.ucfirst(BoxFunctions::getCustomizeName($matchedRoute->module())).'Controller';
        $controllerInstance = new $controllerClass($this, $matchedRoute->module(),$matchedRoute->action());
        return $controllerInstance;
    }

    /**
     * @return HTTPRequest
     */
    public function httpRequest(){
        return $this->httpRequest;
    }

    /**
     * @return HTTPResponse
     */
    public function httpResponse(){
        return $this->httpResponse;
    }
    /**
     * @return User
     */
    public function User(){
        return $this->user;
    }

    abstract public function run();
}
?>