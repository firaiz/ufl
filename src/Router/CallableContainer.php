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
    private Closure $context;

    /**
     * @var array
     */
    private array $params;

    /**
     * ContextContainer constructor.
     * @param mixed $context
     * @param array $params
     */
    public function __construct(?callable $context, array $params = [])
    {
        if (is_null($context)) {
            $context = static function () {};
        }
        $this->context = $context(...);
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
        return true;
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