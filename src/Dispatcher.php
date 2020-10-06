<?php

namespace UflAs;

use UflAs\Router\CallableContainer;
use UflAs\Router\IRouter;
use UflAs\Router\IRouterContainer;
use UflAs\Router\SimpleRouter;

/**
 * Class Dispatcher
 * @package UflAs
 */
class Dispatcher
{
    /**
     * @var static
     */
    protected static $instance;
    /**
     * @var IRouter
     */
    protected $router;

    /**
     * Router constructor.
     */
    final private function __construct()
    {
        // empty
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param IRouter $router
     * @return void
     */
    public function init($router = null)
    {
        if (!($this->router instanceof IRouter) && is_null($router)) {
            $router = new SimpleRouter();
        }

        if ($router instanceof IRouter) {
            $this->router = $router;
        }
    }

    /**
     * @return void
     * @throws Exception\Route\NotFound
     */
    public function dispatch()
    {
        $routeContainer = $this->router->getContainer();
        if ($routeContainer instanceof IRouterContainer && $routeContainer->isValid()) {
            $routeContainer->exec();
            return;
        }
        $routeContainer = $this->router->getNoRoute();
        $routeContainer->exec();
    }

    /**
     * @param callable $closure
     */
    public function initNoRoute($closure)
    {
        $this->router->setNoRoute(new CallableContainer($closure));
    }

    /**
     * @param string $routePath
     * @param mixed $detector
     */
    public function add($routePath, $detector)
    {
        $this->router->add($routePath, $detector);
    }
}