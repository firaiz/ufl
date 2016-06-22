<?php
namespace AnySys;

use AnySys\Router\AbstractRouter;
use AnySys\Router\IRouter;
use AnySys\Router\SimpleRouter;

class Router
{
    /**
     * @var AbstractRouter
     */
    static $router;

    /**
     * @param IRouter $router
     * @return void
     */
    public static function initRouter($router = null)
    {
        if (!(static::$router instanceof IRouter) && is_null($router)) {
            $router = new SimpleRouter();
        }
        static::$router = $router;
    }

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return void
     */
    public static function add($routPath, $detector)
    {
        static::initRouter();
        static::$router->add($routPath, $detector);
    }

    /**
     * detect & call router
     * @return void
     */
    public static function detect()
    {
        static::$router->detect();
    }

    /**
     * @return string
     */
    public static function getPathInfo()
    {
        return static::$router->getPathInfo();
    }
}