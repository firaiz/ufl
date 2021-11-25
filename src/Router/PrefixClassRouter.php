<?php

namespace Ufl\Router;


/**
 * Class PrefixClassRouter
 * @package Ufl\Router
 */
class PrefixClassRouter extends ClassRouter
{
    /**
     * @var PrefixClassRouteAppender[]
     */
    protected $prefixes = array();
    protected $usePrefix = '';

    /**
     * @param string $prefix
     * @return PrefixClassRouteAppender
     */
    public function setPrefix($prefix)
    {
        $this->prefixes[$prefix] = new PrefixClassRouteAppender();
        return $this->prefixes[$prefix];
    }

    public function getPathInfo()
    {
        $pathInfo = parent::getPathInfo();
        if (count($this->prefixes) === 0) {
            return $pathInfo;
        }
        foreach ($this->prefixes as $prefix => $appender) {
            if (strpos($pathInfo, $prefix) === 0) {
                $this->usePrefix = $prefix;
                return preg_replace('#^' . $prefix . '#', '', $pathInfo);
            }
        }
        return '/';
    }

    public function add($routPath, $detector)
    {
        foreach ($this->prefixes as $prefix => $appender) {
            $appender->add($routPath, $detector);
        }
    }

    /**
     * @return array
     */
    protected function getRoutes()
    {
        if (!isset($this->prefixes[$this->usePrefix])) {
            return array();
        }
        /** @var PrefixClassRouteAppender */
        $appender = $this->prefixes[$this->usePrefix];
        return $appender->getRoutes();
    }
}