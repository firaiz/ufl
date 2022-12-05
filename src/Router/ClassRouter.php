<?php

namespace Firaiz\Ufl\Router;

use Firaiz\Ufl\ArrayUtil;

/**
 * Class ClassRouter
 * @package Firaiz\Ufl\Router
 */
class ClassRouter extends AbstractRouter
{

    protected array $routes = array();

    /**
     * @param string $routePath
     * @param mixed $detector
     * @return void
     */
    public function add(string $routePath, mixed $detector): void
    {
        ArrayUtil::set($this->routes, self::pathToKey($routePath), $detector);
    }

    /**
     * @param string $path
     * @return string
     */
    public static function pathToKey(string $path): string
    {
        return ArrayUtil::toKey(explode(static::PATH_SEPARATOR, substr($path, 1)));
    }

    /**
     * @return array
     */
    protected function getRoutes(): array
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    protected function makeContextWithParams(): array
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
        
        $context = null;
        if (class_exists($className) && method_exists($className, $methodName)) {
            $context = array(new $className, $methodName);
        } elseif (class_exists($className) && method_exists($className, 'index')) {
            $context = array(new $className, 'index');
        }
        return array($context, $params);
    }
}