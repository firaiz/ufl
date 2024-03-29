<?php

namespace Firaiz\Ufl;

use Closure;
use Firaiz\Ufl\Router\CallableContainer;
use Firaiz\Ufl\Router\IRouter;
use Firaiz\Ufl\Router\SimpleRouter;
use Firaiz\Ufl\Traits\SingletonTrait;

/**
 * Class Dispatcher
 * @package Firaiz\Ufl
 */
class Dispatcher
{
    use SingletonTrait;

    /**
     * @var ?IRouter
     */
    protected ?IRouter $router = null;

    /**
     * Router constructor.
     */
    final private function __construct()
    {
        // empty
    }

    /**
     * @param IRouter|null $router
     */
    public function init(IRouter $router = null): void
    {
        if (is_null($router)) {
            $router = new SimpleRouter();
        }

        if ($router instanceof IRouter) {
            $this->router = $router;
        }
    }

    /**
     * @throws Exception\Route\NotFound
     */
    public function dispatch(): void
    {
        $routeContainer = $this->router->getContainer();
        if ($routeContainer->isValid()) {
            $routeContainer->exec();
            return;
        }
        $routeContainer = $this->router->getNoRoute();
        $routeContainer->exec();
    }

    public function initNoRoute(Closure $closure): void
    {
        $this->router->setNoRoute(new CallableContainer($closure));
    }

    public function add(string $routePath, mixed $detector): void
    {
        $this->router->add($routePath, $detector);
    }
}