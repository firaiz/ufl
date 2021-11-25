<?php
namespace Ufl\Router;

class PrefixClassRouteAppender
{
    private $routes = array();

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return static
     */
    public function add($routPath, $detector)
    {
        $this->routes[PrefixClassRouter::pathToKey($routPath)] = $detector;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}