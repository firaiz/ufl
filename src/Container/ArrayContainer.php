<?php

namespace Firaiz\Ufl\Container;

/**
 * Class ArrayContainer
 * @package Firaiz\Ufl
 */
class ArrayContainer extends AbstractContainer
{
    /**
     * @return array
     */
    protected function &makeContainer(): array
    {
        static $val = array();
        return $val;
    }
}