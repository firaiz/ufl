<?php
namespace AnySys\Router;

use AnySys\ArrayUtil;

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
     * detect & call route
     */
    public function detect()
    {
        $routeKey = $this->pathToKey(substr($this->getPathInfo(), 1));
        $keys = ArrayUtil::toKeys($routeKey);
        $class = array_shift($keys);
        $method = array_shift($keys);

        $context = ArrayUtil::get($this->routes, ArrayUtil::toKey(array($class, $method)));
        call_user_func_array($context, array($method, $keys));
    }

    /**
     * @param string $path
     * @return string
     */
    protected function pathToKey($path)
    {
        return ArrayUtil::toKey(explode(static::PATH_SEPARATOR, $path));
    }
}