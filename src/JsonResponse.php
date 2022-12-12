<?php

namespace Firaiz\Ufl;

use JsonException;

/**
 * Class JsonResponse
 * @package Firaiz\Ufl
 */
class JsonResponse extends Response
{
    private array $assigns = [];

    /**
     * @param mixed|null $var
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static
    {
        $this->assigns[$name] = $var;
        return $this;
    }

    /**
     * @throws JsonException
     */
    public function write(?string $charset = null): void
    {
        $this->json($this->assigns, $charset);
    }
}