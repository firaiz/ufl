<?php
namespace UflAs;

use UflAs\Router\CallableContainer;
use UflAs\Router\IRouter;
use UflAs\Router\IRouterContainer;
use UflAs\Router\SimpleRouter;

class Router implements IRouter
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
    final protected function __construct()
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
     */
    public function dispatch() {
        $routeContainer = $this->getContainer();
        if ($routeContainer instanceof IRouterContainer) {
            $routeContainer->exec();
            return ;
        }
        $routeContainer = $this->getNoRoute();
        $routeContainer->exec();
    }

    /**
     * @param callable $closure
     */
    public function initNoRoute($closure) {
        $this->setNoRoute(new CallableContainer($closure));
    }

    // ----------------------

    /**
     * @param string $routePath
     * @param mixed $detector
     */
    public function add($routePath, $detector)
    {
        $this->router->add($routePath, $detector);
    }

    /**
     * @return Router\IRouterContainer
     */
    public function getContainer()
    {
        return $this->router->getContainer();
    }

    /**
     * @param IRouterContainer $container
     */
    public function setNoRoute($container)
    {
        $this->router->setNoRoute($container);
    }

    /**
     * @return IRouterContainer
     * @throws \UflAs\Exception\Route\NotFound
     */
    public function getNoRoute()
    {
        return $this->router->getNoRoute();
    }
}