<?php

namespace Firaiz\Ufl\Cache;

use Closure;

/**
 * Class Initializer
 * @package Firaiz\Ufl\Cache
 */
class Initializer
{
    final public const CACHE_TYPE_SQLite3 = 'SQLite3';

    /** @var ?Closure */
    private readonly ?Closure $paramGenerator;

    /**
     * @param string $cacheType
     */
    public function __construct(callable $callable, private $cacheType = self::CACHE_TYPE_SQLite3)
    {
        $this->paramGenerator = $callable(...);
    }

    public function getCacheType(): string
    {
        return $this->cacheType;
    }

    /**
     * Doctrine Cache initial parameters
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