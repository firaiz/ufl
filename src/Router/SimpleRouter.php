<?php
namespace UflAs\Router;

use UflAs\ArrayUtil;

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

    /**
     * @return IRouterContainer
     */
    public function getContainer()
    {
        $routeKey = $this->pathToKey(substr($this->getPathInfo(), 1));
        $params = $keys = ArrayUtil::toKeys($routeKey);
        $context = null;
        foreach ($keys as $key) {
            $tmpContext = ArrayUtil::get($this->routes, $key);
            array_shift($params);
            if (is_callable($tmpContext)) {
                break;
            }
        }
        return $this->initContainer($context, $params);
    }

    /**
     * @param mixed $context
     * @param mixed $params
     * @return IRouterContainer
     */
    function initContainer($context, $params)
    {
        if (!is_array($params)) {
            $params = array();
        }
        return new CallableContainer($context, $params);
    }
}