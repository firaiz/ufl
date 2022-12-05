<?php

namespace Ufl;

use JsonException;

/**
 * Class JsonResponse
 * @package Ufl
 */
class JsonResponse extends Response
{
    private array $assigns = array();

    /**
     * @param array|string $name
     * @param mixed|null $var
     * @param false $noCache
     * @return static
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static
    {
        $this->assigns[$name] = $var;
        return $this;
    }

    /**
     * @param ?string $charset
     * @throws JsonException
     */
    public function write(?string $charset = null): void
    {
        $this->json($this->assigns, $charset);
    }
}