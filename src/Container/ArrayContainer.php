<?php

namespace Ufl\Container;

/**
 * Class ArrayContainer
 * @package Ufl
 */
class ArrayContainer extends AbstractContainer
{
    /**
     * @return array
     */
    protected function &makeContainer()
    {
        static $val = array();
        return $val;
    }
}