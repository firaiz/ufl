<?php
namespace UflAs\Exception\File;

use Exception;

class NotWritable extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("file is not writable", 400, $previous);
    }
}