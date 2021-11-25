<?php

namespace Ufl\Router;

use Ufl\ArrayUtil;

/**
 * Class ClassRouter
 * @package Ufl\Router
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
                foreach (array(reset($params), 'index') as $methodName) {
                    if (method_exists($className, $methodName)) {
                        if ($methodName === reset($params)) {
                            array_shift($params);
                        }
                        break 2;
                    }
                }
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