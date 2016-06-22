<?php
namespace AnySys\Router;

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
     * detect & call router
     * @return void
     */
    public function detect();
}