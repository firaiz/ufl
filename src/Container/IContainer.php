<?php

namespace Ufl\Container;

/**
 * Interface IContainer
 * @package Ufl\Container
 */
interface IContainer
{
    /**
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null);

    /**
     * @param string $name
     * @param string $value
     * @return void
     */
    public function set($name, $value);

    /**
     * @param string $name
     */
    public function del($name = null);
}