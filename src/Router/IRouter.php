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
    public function add(string $routePath, mixed $detector): void;

    /**
     * @return IRouterContainer
     */
    public function getContainer(): IRouterContainer;

    /**
     * @param IRouterContainer $container
     */
    public function setNoRoute(IRouterContainer $container);

    /**
     * @return IRouterContainer
     * @throws NotFound
     */
    public function getNoRoute(): IRouterContainer;
}