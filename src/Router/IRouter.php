<?php
namespace UflAs\Router;

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
     * @throws \UflAs\Exception\Route\NotFound
     */
    public function getNoRoute();
}