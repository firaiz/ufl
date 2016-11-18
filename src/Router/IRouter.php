<?php
namespace UflAs\Router;

interface IRouter
{
    /**
     * uri path separator
     */
    const PATH_SEPARATOR = '/';

    /**
     * @param string $routPath
     * @param mixed $detector
     * @return void
     */
    public function add($routPath, $detector);

    /**
     * @return IRouterContainer
     */
    public function getContainer();
}