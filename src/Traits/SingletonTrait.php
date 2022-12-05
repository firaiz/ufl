<?php

namespace Firaiz\Ufl\Traits;

trait SingletonTrait
{
    protected static mixed $instance = null;
    public static function getInstance(): static
    {
        if (!(self::$instance instanceof static)) {
            self::$instance = new static;
        }
        return self::$instance;
    }
}