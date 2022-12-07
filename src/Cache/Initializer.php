<?php

namespace Firaiz\Ufl\Cache;

use Closure;

/**
 * Class Initializer
 * @package Firaiz\Ufl\Cache
 */
class Initializer
{
    public const CACHE_TYPE_SQLite3 = 'SQLite3';

    /** @var ?Closure */
    private ?Closure $paramGenerator = null;
    /** @var string */
    private mixed $cacheType;

    public function __construct(Closure $callable, $cacheType = self::CACHE_TYPE_SQLite3)
    {
        $this->paramGenerator = $callable;
        $this->cacheType = $cacheType;
    }

    /**
     * @return string
     */
    public function getCacheType(): string
    {
        return $this->cacheType;
    }

    /**
     * Doctrine Cache initial parameters
     * @return Closure
     *
     * callable example for SQLite cache
     * function () use($path) {
     *  return [new SQLite3($path), 'exampleCache'];
     * }
     */
    public function getParamGenerator(): Closure
    {
        return $this->paramGenerator;
    }
}