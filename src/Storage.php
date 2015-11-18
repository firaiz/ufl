<?php

namespace AnySys;

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
    /** @var string base path */
    protected $filePath = null;

    const DS = DIRECTORY_SEPARATOR;

    /**
     * Storage constructor.
     */
    protected function __construct()
    {
        $this->filePath = defined('BASE_DIR') ? BASE_DIR : dirname(dirname(dirname(dirname(__FILE__))));

        if (!file_exists($this->filePath)) {
            throw new NotFoundException();
        } else if (!is_writable($this->filePath)) {
            throw new NotWritableException();
        }
    }

    /**
     * @return static
     */
    public static function getInstance()
    {

        if (!(self::$instance instanceof static)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return string
     */
    public function base()
    {
        return $this->filePath;
    }

    /**
     * @param string $path
     * @param boolean $isCreate
     * @return string is fullpath
     */
    public function getPath($path, $isCreate = false)
    {
        if ($isCreate === false || $isCreate && $this->create($path)) {
            return realpath($this->base()) . self::DS . $this->replace($path);
        }
    }

    /**
     * @param string $path
     * @param int $permission octet number
     * @return bool
     */
    public function create($path, $permission = 0700)
    {
        $dirPath = $this->base() . self::DS . $this->replace($path);
        if (is_writable($this->base())) {
            return is_dir($dirPath) ? true : mkdir($dirPath, $permission, true);
        }
        return false;
    }

    /**
     * @param string $path
     * @return string
     */
    protected function replace($path)
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}