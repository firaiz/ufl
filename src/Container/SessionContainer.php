<?php

namespace Ufl\Container;

use Ufl\Session;

/**
 * Class SessionContainer
 * @package Ufl
 */
class SessionContainer extends AbstractContainer
{
    protected function &makeContainer(): array
    {
        return Session::getInstance()->getContainer();
    }
}