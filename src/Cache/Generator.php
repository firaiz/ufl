<?php

namespace Firaiz\Ufl\Cache;

use Doctrine\Common\Cache\CacheProvider;
use ReflectionClass;
use ReflectionException;
use Firaiz\Ufl\Exception\Cache\ClassNotFound;

/**
 * Class Generator
 * @package Firaiz\Ufl\Cache
 */
class Generator
{
    /**
     * @throws ClassNotFound
     * @throws ReflectionException
     */
    public static function generate(Initializer $initializer): CacheProvider
    {
        $callParams = call_user_func($initializer->getParamGenerator());
        $className = '\\Doctrine\\Common\\Cache\\' . $initializer->getCacheType() . 'Cache';
        if (!class_exists($className)) {
            throw new ClassNotFound();
        }

        if (!is_array($callParams)) {
            return new $className();
        }

        return (new ReflectionClass($className))->newInstanceArgs($callParams);
    }
}