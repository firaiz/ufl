<?php

namespace Ufl\Router;

use Ufl\Exception\Route\NotFound;

/**
 * Interface IRouter
 * @package Ufl\Router
 */
interface IRouter
{

    /**
     * @param string $routePath
     * @param mixed $detector
     * @return void
     */
    public function add($routePath, $detector);

    /**
     * @return IRouterContainer
     */
    public function getContainer();

    /**
     * @param IRouterContainer $container
     */
    public function setNoRoute($container);

    /**
     * @return IRouterContainer
     * @throws NotFound
     */
    public function getNoRoute();
}