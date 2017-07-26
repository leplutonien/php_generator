<?php
namespace lib;

/**
 * class to retrieve all  routes
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class  Router {
    protected $routes = array();
    const NO_ROUTE = 1;

    public function addRoute(Route $route){
        if (!in_array($route, $this->routes)){
            $this->routes[] = $route;
        }
    }

    public function routes(){
        return $this->routes;
    }

    /**
     * @param $url
     * @return Route
     */
    public function getRoute($url,$method='get'){
        $found = false;
        foreach ($this->routes as $route){
            // Si la route correspond à l'URL.
            if (($varsValues = $route->match($url,$method)) !== false){
                if ($route->hasVars()){
                    $varsNames = $route->varsNames();
                    $listVars = array();
                    foreach ($varsValues as $key => $match){
                        if ($key !== 0){
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }
                    $route->setVars($listVars);
                }
                $found = true;
                break;
            }
        }
        if($found)
            return $route;
        else
            throw new \RuntimeException('No route to  the URL', self::NO_ROUTE);
    }
}

?>