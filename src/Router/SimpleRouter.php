<?php

namespace Ufl\Router;

use Ufl\ArrayUtil;

/**
 * Class SimpleRouter
 * @package Ufl\Router
 */
class SimpleRouter extends AbstractRouter
{

    protected $routes = array();

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return void
     */
    public function add($routPath, $detector)
    {
        ArrayUtil::set($this->routes, $this->pathToKey(substr($routPath, 1)), $detector);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function pathToKey($path)
    {
        return ArrayUtil::toKey(explode(static::PATH_SEPARATOR, $path));
    }

    public function makeContextWithParams()
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