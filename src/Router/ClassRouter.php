<?php

namespace UflAs\Router;

use UflAs\ArrayUtil;

/**
 * Class ClassRouter
 * @package UflAs\Router
 */
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
        ArrayUtil::set($this->routes, self::pathToKey($routPath), $detector);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function pathToKey($path)
    {
        return ArrayUtil::toKey(explode(static::PATH_SEPARATOR, substr($path, 1)));
    }

    protected function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    protected function makeContextWithParams()
    {
        $routeKey = self::pathToKey($this->getPathInfo());
        $params = $keys = ArrayUtil::toKeys($routeKey);
        $routes = $this->getRoutes();

        $className = '';
        $methodName = '';

        $detectKeys = array($keys[0]);
        if (isset($keys[1])) {
            $detectKeys[] = $keys[1];
        }
        foreach ($detectKeys as $key) {
            $routes = ArrayUtil::get($routes, $key);
            if (is_string($routes)) {
                $className = $routes;
                array_shift($params);
                $methodName = array_shift($params);
                $methodName = is_null($methodName) ? 'index' : $methodName;
            } elseif (is_array($routes) && isset($routes['class'], $routes['method'])) {
                $className = $routes['class'];
                $methodName = $routes['method'];
                array_shift($params);
                array_shift($params);
            }
        }

        $obj = null;
        $context = null;
        if (class_exists($className) && method_exists($className, $methodName)) {
            $context = array(new $className, $methodName);
        } elseif (class_exists($className) && method_exists($className, 'index')) {
            $context = array(new $className, 'index');
        }
        return array($context, $params);
    }
}