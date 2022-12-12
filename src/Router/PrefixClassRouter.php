<?php

namespace Firaiz\Ufl\Router;


/**
 * Class PrefixClassRouter
 * @package Firaiz\Ufl\Router
 */
class PrefixClassRouter extends ClassRouter
{
    /**
     * @var PrefixClassRouteAppender[]
     */
    protected array $prefixes = [];
    protected string $usePrefix = '';

    public function setPrefix(string $prefix): PrefixClassRouteAppender
    {
        $this->prefixes[$prefix] = new PrefixClassRouteAppender();
        return $this->prefixes[$prefix];
    }

    public function getPathInfo(): string
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

    public function add(string $routePath, mixed $detector): void
    {
        foreach ($this->prefixes as $appender) {
            $appender->add($routePath, $detector);
        }
    }

    protected function getRoutes(): array
    {
        if (!isset($this->prefixes[$this->usePrefix])) {
            return [];
        }
        return $this->prefixes[$this->usePrefix]->getRoutes();
    }
}