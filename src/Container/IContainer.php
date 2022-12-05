<?php

namespace Firaiz\Ufl\Container;

/**
 * Interface IContainer
 * @package Firaiz\Ufl\Container
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
     */
    public function set(string $name, mixed $value): void;

    /**
     * @param string|null $name
     */
    public function del(string $name = null): void;
}