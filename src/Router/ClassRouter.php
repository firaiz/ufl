<?php
namespace UflAs\Router;

use UflAs\ArrayUtil;

class ClassRouter extends AbstractRouter
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
        $context = $this->routes;

        $className = '';
        $methodName = '';

        foreach (array($keys[0], $keys[1]) as $key) {
            $context = ArrayUtil::get($context, $key);
            if (is_string($context)) {
                $className = $context;
                array_shift($params);
                $methodName = array_shift($params);
            } elseif (is_array($context) && isset($context['class'], $context['method'])) {
                $className = $context['class'];
                $methodName = $context['method'];
                array_shift($params);
                array_shift($params);
            }
        }

        $obj = null;
        if (class_exists($className)) {
            $obj = new $className;
        }
        if (method_exists($obj, $methodName)) {
            $context = array($obj, $methodName);
        } else {
            $context = null;
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