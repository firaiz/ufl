<?php

namespace UflAs\Exception\File;

use Exception;

/**
 * Class NotFound
 * @package UflAs\Exception\File
 */
class NotFound extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("file not found", 404, $previous);
    }
}