<?php
namespace AnySys;

use AnySys\Router\AbstractRouter;
use AnySys\Router\SimpleRouter;

class Router
{
    /**
     * @var static
     */
    protected static $instance;
    /**
     * @var AbstractRouter
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
     * @param AbstractRouter $router
     * @return void
     */
    public function init($router = null)
    {
        if (!($this->router instanceof AbstractRouter) && is_null($router)) {
            $router = new SimpleRouter();
        }

        if ($router instanceof AbstractRouter) {
            $this->router = $router;
        }
    }

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return void
     */
    public function add($routPath, $detector)
    {
        $this->router->add($routPath, $detector);
    }

    /**
     * detect & call router
     * @return void
     */
    public function detect()
    {
        $this->router->detect();
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        return $this->router->getPathInfo();
    }

    /**
     * @param \Closure $closure
     */
    public function setNoRoute($closure)
    {
        $this->router->setNoRoute($closure);
    }
}