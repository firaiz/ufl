<?php
namespace Ufl\Router;

class PrefixClassRouteAppender
{
    private array $routes = array();

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return static
     */
    public function add(string $routPath, mixed $detector)
    {
        $this->routes[PrefixClassRouter::pathToKey($routPath)] = $detector;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }
}