<?php

namespace Ays;

use Exception;

class NotFoundException extends Exception {
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("dir not found", 404, $previous);
    }
}
class NotWritableException extends Exception {
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("dir is not writable", 400, $previous);
    }
}

class Storage
{
    /** @var static */
    protected static $instance = null;

    protected $filePath = null;

    const DS = DIRECTORY_SEPARATOR;

    protected function __construct()
    {
        $this->filePath = defined('BASE_DIR') ? BASE_DIR : dirname(dirname(dirname(__FILE__)));

        if (!file_exists($this->filePath)) {
            throw new NotFoundException();
        } else if (!is_writable($this->filePath)) {
            throw new NotWritableException();
        }
    }

    public static function getInstance()
    {

        if (!(self::$instance instanceof static)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function base()
    {
        return $this->filePath;
    }

    public function getPath($path)
    {
        return realpath($this->base() . self::DS . $this->replace($path));
    }

    public function create($path, $permission = 0700)
    {
        $dirPath = $this->base() . self::DS . $this->replace($path);
        if (is_writable($this->base())) {
            return mkdir($dirPath, $permission, true);
        }
        return false;
    }

    protected function replace($path)
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}