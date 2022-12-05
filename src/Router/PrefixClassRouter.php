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
    protected array $prefixes = array();
    protected string $usePrefix = '';

    /**
     * @param string $prefix
     * @return PrefixClassRouteAppender
     */
    public function setPrefix(string $prefix): PrefixClassRouteAppender
    {
        $this->prefixes[$prefix] = new PrefixClassRouteAppender();
        return $this->prefixes[$prefix];
    }

    public function getPathInfo(): array|string|null
    {
        $pathInfo = parent::getPathInfo();
        if (count($this->prefixes) === 0) {
            return $pathInfo;
        }
        foreach ($this->prefixes as $prefix => $appender) {
            if (str_starts_with($pathInfo, $prefix)) {
                $this->usePrefix = $prefix;
                return preg_replace('#^' . $prefix . '#', '', $pathInfo);
            }
        }
        return '/';
    }

    /**
     * @param string $routePath
     * @param mixed $detector
     * @return void
     */
    public function add(string $routePath, mixed $detector): void
    {
        foreach ($this->prefixes as $prefix => $appender) {
            $appender->add($routePath, $detector);
        }
    }

    /**
     * @return array
     */
    protected function getRoutes(): array
    {
        if (!isset($this->prefixes[$this->usePrefix])) {
            return array();
        }
        return $this->prefixes[$this->usePrefix]->getRoutes();
    }
}