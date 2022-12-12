<?php

namespace Firaiz\Ufl\Container;

/**
 * Class ArrayContainer
 * @package Firaiz\Ufl
 */
class ArrayContainer extends AbstractContainer
{
    protected function &makeContainer(): array
    {
        static $val = [];
        return $val;
    }
}