<?php

namespace UflAs\Exception\File;

use Exception;

/**
 * Class NotWritable
 * @package UflAs\Exception\File
 */
class NotWritable extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct(sprintf('file is not writable (%s)', $message), 400, $previous);
    }
}