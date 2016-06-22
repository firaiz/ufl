<?php
namespace AnySys\Router;

abstract class AbstractRouter implements IRouter
{
    protected static $pathInfo = '';

    /**
     * @return string
     */
    public function getPathInfo()
    {
        if (!static::$pathInfo) {
            $selfUri = str_replace(DIRECTORY_SEPARATOR, '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME'])));
            static::$pathInfo = str_replace($selfUri, '', $_SERVER['PATH_INFO']);
        }
        return static::$pathInfo;
    }
}