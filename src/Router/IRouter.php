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
     * detect & call router
     * @return void
     * @throws \UflAs\Exception\Route\NotFound
     */
    public function detect();
}