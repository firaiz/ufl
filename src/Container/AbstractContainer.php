<?php

namespace Firaiz\Ufl\Container;

use Firaiz\Ufl\ArrayUtil;

/**
 * Class AbstractContainer
 * @package Firaiz\Ufl\Container
 */
abstract class AbstractContainer implements IContainer
{

    /** @var ?string */
    protected ?string $prefix = null;

    /** @var mixed */
    protected mixed $container = null;

    /**
     * Session constructor.
     * @param string|null $prefix
     */
    public function __construct(string $prefix = null)
    {
        if (is_string($prefix)) {
            $this->prefix = $prefix;
        }
    }

    /**
     * unset session
     * @param string|null $name
     */
    public function del(string $name = null): void
    {
        $container =& $this->getContainer();
        if (is_null($name)) {
            $container = array();
            return;
        }

        $keys = ArrayUtil::toKeys($name);
        $key = $name;
        if (count($keys) === 1) {
            if (array_key_exists($key, $container)) {
                unset($container[$key]);
            }
            return;
        }

        $lastKey = array_pop($keys);
        $key = ArrayUtil::toKey($keys);
        $setValue = ArrayUtil::get($container, $key, array());
        if (array_key_exists($lastKey, $setValue)) {
            unset($setValue[$lastKey]);
        }

        $this->set($key, $setValue);
    }

    /**
     * @return mixed
     */
    protected function &getContainer(): mixed
    {
        if (!is_array($this->container)) {
            $this->initContainer();
        }
        return $this->container;
    }

    /**
     * init session container
     */
    protected function initContainer(): void
    {
        $container =& $this->makeContainer();
        if (is_string($this->prefix)) {
            if (!array_key_exists($this->prefix, $container)) {
                $container[$this->prefix] = array();
            }
            $this->container =& $container[$this->prefix];
            return;
        }
        $this->container =& $container;
    }

    abstract protected function &makeContainer();

    /**
     * @param string $name
     * @param mixed $value
     */
    public function set(string $name, mixed $value): void
    {
        ArrayUtil::set($this->getContainer(), $name, $value);
    }

    /**
     * get session data
     * @param $name
     * @param mixed|null $default
     * @return mixed
     */
    public function get($name, mixed $default = null):mixed
    {
        return ArrayUtil::get($this->getContainer(), $name, $default);
    }
}