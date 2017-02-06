<?php
namespace lib;

/**
 * class to retrieve all  routes
 * @author PhpGenerator (https://github.com/leplutonien/php_generator)
 */
class  Router
{
    const NO_ROUTE = 1;
    protected $routes = array();

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes)) {
            $this->routes[] = $route;
        }
    }

    /**
     * @param $url
     * @return Route
     */
    public function getRoute($url)
    {
        $result = false;
        foreach ($this->routes as $route) {
            // Si la route correspond à l'URL.
            if (($varsValues = $route->match($url)) !== false) {
                if ($route->hasVars()) {
                    $varsNames = $route->varsNames();
                    $listVars = array();
                    foreach ($varsValues as $key => $match) {
                        if ($key !== 0) {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }
                    $route->setVars($listVars);
                }
                $result = true;
                break;
            }
        }

        if ($result)
            return $route;
        else
            throw new \RuntimeException('No route to  the URL', self::NO_ROUTE);
    }
}

?>