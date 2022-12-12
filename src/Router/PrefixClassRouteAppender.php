<?php
namespace Firaiz\Ufl\Router;

class PrefixClassRouteAppender
{
    private array $routes = [];

    public function add(string $routPath, mixed $detector): static
    {
        $this->routes[PrefixClassRouter::pathToKey($routPath)] = $detector;
        return $this;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}