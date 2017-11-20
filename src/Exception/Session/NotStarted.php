<?php
namespace UflAs\Exception\Session;

use Exception;

class NotStarted extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("session is not started", 503, $previous);
    }
}