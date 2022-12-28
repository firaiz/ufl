<?php

namespace Firaiz\Ufl\Traits;

trait GetSetPropertiesTrait
{
    private array $properties = [];
    public function __get(string $name)
    {
        return $this->properties[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->properties[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return isset($this->properties[$name]);
    }

    protected function getProperties(): array
    {
        return $this->properties;
    }
}