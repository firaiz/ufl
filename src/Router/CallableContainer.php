<?php

namespace Firaiz\Ufl\Router;

use Closure;

/**
 * Class CallableContainer
 * @package Firaiz\Ufl\Router
 */
class CallableContainer implements IRouterContainer
{
    /**
     * @var mixed
     */
    private readonly Closure $context;

    /**
     * ContextContainer constructor.
     * @param mixed $context
     */
    public function __construct(?callable $context, private array $params = [])
    {
        if (is_null($context)) {
            $context = static function () {};
        }
        $this->context = $context(...);
    }

    public function exec(): void
    {
        if ($this->isValid()) {
            call_user_func_array($this->context, $this->getParams());
        }
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): void
    {
        $this->params = $params;
    }
}