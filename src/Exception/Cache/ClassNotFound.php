<?php

namespace UflAs\Exception\Cache;

use Exception;

/**
 * Class ClassNotFound
 * @package UflAs\Exception\Cache
 */
class ClassNotFound extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("cache class not found", 404, $previous);
    }
}