<?php

namespace Firaiz\Ufl\Router;

/**
 * Interface IRouterContainer
 * @package Firaiz\Ufl\Router
 */
interface IRouterContainer
{
    /**
     * @return void
     */
    public function exec(): void;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @return array
     */
    public function getParams(): array;

    /**
     * @param array $params
     */
    public function setParams(array $params);
}