<?php

namespace Firaiz\Ufl\Exception\Session;

use Exception;

/**
 * Class NotStarted
 * @package Firaiz\Ufl\Exception\Session
 */
class NotStarted extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("session is not started", 503, $previous);
    }
}