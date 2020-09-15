<?php

namespace UflAs\Cache;

/**
 * Class Initializer
 * @package UflAs\Cache
 */
class Initializer
{
    const CACHE_TYPE_SQLite3 = 'SQLite3';

    /** @var Callable */
    private $paramGenerator;
    /** @var string */
    private $cacheType;

    public function __construct($callable, $cacheType = self::CACHE_TYPE_SQLite3)
    {
        $this->paramGenerator = $callable;
        $this->cacheType = $cacheType;
    }

    /**
     * @return string
     */
    public function getCacheType()
    {
        return $this->cacheType;
    }

    /**
     * Doctrine Cache initial parameters
     * @return Callable
     *
     * callable example for SQLite cache
     * function () use($path) {
     *  return [new SQLite3($path), 'exampleCache'];
     * }
     */
    public function getParamGenerator()
    {
        return $this->paramGenerator;
    }
}