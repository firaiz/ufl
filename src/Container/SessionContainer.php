<?php

namespace UflAs\Container;

use UflAs\Session;

/**
 * Class SessionContainer
 * @package UflAs
 */
class SessionContainer extends AbstractContainer
{
    protected function &makeContainer()
    {
        return Session::getInstance()->getContainer();
    }
}