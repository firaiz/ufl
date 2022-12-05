<?php

namespace Firaiz\Ufl\Router;

use Firaiz\Ufl\ArrayUtil;

/**
 * Class SimpleRouter
 * @package Firaiz\Ufl\Router
 */
class SimpleRouter extends AbstractRouter
{

    protected array $routes = array();

    /**
     * @param string $routePath
     * @param mixed $detector
     * @return void
     */
    public function add(string $routePath, mixed $detector): void
    {
        ArrayUtil::set($this->routes, $this->pathToKey(substr($routePath, 1)), $detector);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function pathToKey(string $path): string
    {
        return ArrayUtil::toKey(explode(static::PATH_SEPARATOR, $path));
    }

    public function makeContextWithParams(): array
    {
        $routeKey = $this->pathToKey(substr($this->getPathInfo(), 1));
        $params = $keys = ArrayUtil::toKeys($routeKey);
        $context = $this->routes;
        foreach ($keys as $key) {
            $context = ArrayUtil::get($context, $key);
            array_shift($params);
            if (is_callable($context)) {
                break;
            }
        }
        return array($context, $params);
    }
}