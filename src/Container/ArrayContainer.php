<?php

namespace UflAs\Container;

/**
 * Class ArrayContainer
 * @package UflAs
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