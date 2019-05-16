<?php

namespace UflAs\Container;

/**
 * Class ArrayContainer
 * @package UflAs
 */
class ArrayContainer extends AbstractContainer
{
    protected function &makeContainer()
    {
        static $val = array();
        return $val;
    }
}