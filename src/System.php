<?php


namespace UflAs;

class System
{
    public static function path()
    {
        return defined('SYSTEM_DIR') ? SYSTEM_DIR : dirname(dirname(dirname(dirname(__DIR__))));
    }

    public static function templatePath()
    {
        return static::path() . DIRECTORY_SEPARATOR .
            Config::getInstance()->get('render.config.general.dir', 'storage/template');
    }
}