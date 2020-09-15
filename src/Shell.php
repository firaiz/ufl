<?php

namespace UflAs;

class Shell
{
    /**
     * @param $commandParams
     * @return string
     */
    public static function exec($commandParams)
    {
        return exec(self::toCommand($commandParams));
    }

    /**
     * @param $commandParams
     * @return string
     */
    protected static function toCommand($commandParams)
    {
        return implode(' ', $commandParams);
    }

    /**
     * @param $commandParams
     * @return false|string
     */
    public static function system($commandParams)
    {
        return system(self::toCommand($commandParams));
    }

    /**
     * @param array $params
     * @param string $logPath
     * @return string
     */
    public static function noWaitExec($params = array(), $logPath = null)
    {
        $commandParams = array_merge(
            array('nohup'),
            $params,
            array('>', is_null($logPath) ? '/dev/null' : $logPath, '&')
        );
        return static::exec($commandParams);
    }
}