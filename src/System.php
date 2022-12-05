<?php


namespace Firaiz\Ufl;

class System
{
    public static function path(): string
    {
        /** @noinspection PhpUndefinedConstantInspection */
        return defined('SYSTEM_DIR') ? SYSTEM_DIR : dirname(__DIR__, 4);
    }

    public static function templatePath(): string
    {
        return static::path() . DIRECTORY_SEPARATOR .
            Config::getInstance()->get('render.config.general.dir', 'storage/template');
    }
}