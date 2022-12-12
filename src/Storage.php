<?php

namespace Firaiz\Ufl;

use Firaiz\Ufl\Exception\File\NotFound;
use Firaiz\Ufl\Exception\File\NotWritable;
use Firaiz\Ufl\Traits\SingletonTrait;

class Storage
{
    final public const DS = DIRECTORY_SEPARATOR;
    final public const DEFAULT_PERMISSION = 0755;

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
     * @param int $permission octet number
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

    public function base(): string
    {
        return realpath($this->filePath);
    }

    protected function replace(string $path): string
    {
        return str_replace(
            $this->base().self::DS.'storage',
            $this->base(),
            str_replace(
                ['/', '\\'],
                [DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR],
                preg_replace('#^storage/#', '', $path)
            )
        );
    }
}