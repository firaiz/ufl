<?php

namespace Ufl\Exception\File;

use Exception;

/**
 * Class NotFound
 * @package Ufl\Exception\File
 */
class NotFound extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('file not found %s', $message), 404, $previous);
    }
}