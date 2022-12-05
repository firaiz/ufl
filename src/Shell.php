<?php

namespace Ufl;

class Shell
{
    /**
     * @param $commandParams
     * @return string
     */
    public static function exec($commandParams): string
    {
        return exec(self::toCommand($commandParams));
    }

    /**
     * @param $commandParams
     * @return string
     */
    protected static function toCommand($commandParams): string
    {
        return implode(' ', $commandParams);
    }

    /**
     * @param $commandParams
     * @return false|string
     */
    public static function system($commandParams): bool|string
    {
        return system(self::toCommand($commandParams));
    }

    /**
     * @param array $params
     * @param string|null $logPath
     * @return string
     */
    public static function noWaitExec(array $params = array(), string $logPath = null): string
    {
        $commandParams = array_merge(
            array('nohup'),
            $params,
            array('>', is_null($logPath) ? '/dev/null' : $logPath, '&')
        );
        return static::exec($commandParams);
    }
}