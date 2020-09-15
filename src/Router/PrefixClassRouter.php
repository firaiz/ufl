<?php

namespace UflAs\Router;

/**
 * Class PrefixClassRouter
 * @package UflAs\Router
 */
class PrefixClassRouter extends ClassRouter
{
    protected $prefix = null;

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getPathInfo()
    {
        $pathInfo = parent::getPathInfo();
        if (is_null($this->prefix)) {
            return $pathInfo;
        }
        if (strpos($pathInfo, $this->prefix) !== 0) {
            return '/';
        }
        return preg_replace('#^' . $this->prefix . '#', '', $pathInfo);
    }
}