<?php

namespace Ufl\Router;

use Closure;

/**
 * Class CallableContainer
 * @package Ufl\Router
 */
class CallableContainer implements IRouterContainer
{
    /**
     * @var callable
     */
    private $context;

    /**
     * @var array
     */
    private array $params;

    /**
     * ContextContainer constructor.
     * @param Closure $context
     * @param array $params
     */
    public function __construct(Closure $context, array $params = [])
    {
        $this->context = $context;
        $this->params = $params;
    }

    public function exec(): void
    {
        if ($this->isValid()) {
            call_user_func_array($this->context, $this->getParams());
        }
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return is_callable($this->context);
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}