<?php

namespace Ufl\Cache;

use Doctrine\Common\Cache\CacheProvider;
use ReflectionClass;
use ReflectionException;
use Ufl\Exception\Cache\ClassNotFound;

/**
 * Class Generator
 * @package Ufl\Cache
 */
class Generator
{
    /**
     * @param Initializer $initializer
     * @return CacheProvider
     * @throws ClassNotFound
     * @throws ReflectionException
     */
    public static function generate($initializer)
    {
        $callParams = call_user_func($initializer->getParamGenerator());
        $className = '\\Doctrine\\Common\\Cache\\' . $initializer->getCacheType() . 'Cache';
        if (!class_exists($className)) {
            throw new ClassNotFound();
        }

        if (!is_array($callParams)) {
            return new $className();
        }

        $reflection = new ReflectionClass($className);
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return $reflection->newInstanceArgs($callParams);
    }
}