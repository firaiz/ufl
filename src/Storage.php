<?php

namespace UflAs;

use UflAs\Exception\File\NotFound;
use UflAs\Exception\File\NotWritable;

class Storage
{
    const DS = DIRECTORY_SEPARATOR;
    const DEFAULT_PERMISSION = 0700;


    /** @var static */
    protected static $instance = null;
    /** @var string base path */
    protected $filePath = null;

    /**
     * Storage constructor.
     * @throws NotFound
     * @throws NotWritable
     */
    protected function __construct()
    {
        $this->filePath = defined('BASE_DIR') ? BASE_DIR : dirname(dirname(dirname(dirname(__FILE__))));

        if (!file_exists($this->filePath)) {
            throw new NotFound();
        } elseif (!is_writable($this->filePath)) {
            throw new NotWritable();
        }
    }

    /**
     * @return static
     * @throws NotFound
     * @throws NotWritable
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof static)) {
            self::$instance = new static();
        }
        return self::$instance;
    }

    /**
     * @param string $path
     * @param boolean $isCreate
     * @param int $permission
     * @return string is fullpath
     */
    public function getPath($path, $isCreate = false, $permission = self::DEFAULT_PERMISSION)
    {
        if ($isCreate === false || $isCreate && $this->create($path, $permission)) {
            return realpath($this->base()) . self::DS . $this->replace($path);
        }
        return '';
    }

    /**
     * @param string $path
     * @param int $permission octet number
     * @return bool
     */
    public function create($path, $permission = self::DEFAULT_PERMISSION)
    {
        $dirPath = $this->base() . self::DS . $this->replace($path);
        if (is_writable($this->base())) {
            return is_dir($dirPath) ? true : mkdir($dirPath, $permission, true);
        }
        return false;
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
     * @return string
     */
    protected function replace($path)
    {
        return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    }
}