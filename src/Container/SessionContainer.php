<?php

namespace Firaiz\Ufl\Container;

use Firaiz\Ufl\Session;

/**
 * Class SessionContainer
 * @package Firaiz\Ufl
 */
class SessionContainer extends AbstractContainer
{
    protected function &makeContainer(): array
    {
        return Session::getInstance()->getContainer();
    }
}