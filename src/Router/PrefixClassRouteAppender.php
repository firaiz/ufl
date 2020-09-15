<?php
namespace UflAs\Router;

class PrefixClassRouteAppender
{
    private $routes = array();

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return void
     */
    public function add($routPath, $detector)
    {
        $this->routes[PrefixClassRouter::pathToKey($routPath)] = $detector;
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}