<?php

namespace UflAs\Container;

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