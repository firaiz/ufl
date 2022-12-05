<?php

namespace Ufl;

use Ufl\Exception\File\NotFound;
use Ufl\Exception\File\NotWritable;
use Ufl\Traits\SingletonTrait;

class Storage
{
    public const DS = DIRECTORY_SEPARATOR;
    public const DEFAULT_PERMISSION = 0755;

    use SingletonTrait;

    /** @var string base path */
    protected string $filePath;

    /**
     * Storage constructor.
     * @throws NotFound
     * @throws NotWritable
     */
    protected function __construct()
    {
        /** @noinspection PhpUndefinedConstantInspection */
        $this->filePath = defined('STORAGE_DIR') ?
            STORAGE_DIR : dirname(__DIR__, 4) .DIRECTORY_SEPARATOR.'storage';

        if (!file_exists($this->filePath)) {
            throw new NotFound($this->filePath);
        }

        if (!is_writable($this->filePath)) {
            throw new NotWritable($this->filePath);
        }
    }

    /**
     * @param string $path
     * @param bool $isCreate
     * @param int $permission
     * @return string is full-path
     */
    public function getPath(string $path, bool $isCreate = false, int $permission = self::DEFAULT_PERMISSION): string
    {
        if ($isCreate === false || ($isCreate && $this->create($path, $permission))) {
            return $this->base() . self::DS . $this->replace($path);
        }
        return '';
    }

    /**
     * @param string $path
     * @param int $permission octet number
     * @return bool
     */
    public function create(string $path, int $permission = self::DEFAULT_PERMISSION): bool
    {
        $dirPath = $this->base() . self::DS . $this->replace($path);
        if (is_writable($this->base())) {
            if (is_dir($dirPath)) {
                return true;
            }
            $oldMask = umask(0);
            $makeStatus = @mkdir($dirPath, $permission, true);
            umask($oldMask);
            return $makeStatus;
        }
        return false;
    }

    /**
     * @return string
     */
    public function base(): string
    {
        return realpath($this->filePath);
    }

    /**
     * @param string $path
     * @return string
     */
    protected function replace(string $path): string
    {
        return str_replace(
            $this->base().self::DS.'storage',
            $this->base(),
            str_replace(
                array('/', '\\'),
                array(DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR),
                preg_replace('#^storage/#', '', $path)
            )
        );
    }
}