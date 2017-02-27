<?php
namespace UflAs;

class Shell
{
    public static function exec($commandParams) {
        return exec(self::toCommand($commandParams));
    }

    protected static function toCommand($commandParams) {
        return implode(' ', $commandParams);
    }

    public static function system($commandParams) {
        return system(self::toCommand($commandParams));
    }
}