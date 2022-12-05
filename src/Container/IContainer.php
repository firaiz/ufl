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
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, mixed $default = null): mixed;

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function set(string $name, mixed $value);

    /**
     * @param string|null $name
     */
    public function del(string $name = null);
}