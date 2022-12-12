<?php

namespace Firaiz\Ufl;

class Shell
{
    /**
     * @param $commandParams
     */
    public static function exec($commandParams): string
    {
        return exec(self::toCommand($commandParams));
    }

    /**
     * @param $commandParams
     */
    protected static function toCommand($commandParams): string
    {
        return implode(' ', $commandParams);
    }

    /**
     * @param $commandParams
     */
    public static function system($commandParams): bool|string
    {
        return system(self::toCommand($commandParams));
    }

    /**
     * @param string|null $logPath
     */
    public static function noWaitExec(array $params = [], string $logPath = null): string
    {
        $commandParams = array_merge(
            ['nohup'],
            $params,
            ['>', is_null($logPath) ? '/dev/null' : $logPath, '&']
        );
        return static::exec($commandParams);
    }
}