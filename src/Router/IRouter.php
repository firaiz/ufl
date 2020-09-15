<?php

namespace UflAs\Router;

use UflAs\Exception\Route\NotFound;

/**
 * Interface IRouter
 * @package UflAs\Router
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