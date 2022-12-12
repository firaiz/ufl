<?php

namespace Firaiz\Ufl;


use Firaiz\Ufl\Traits\SingletonTrait;

class Session
{
    use SingletonTrait;

    protected bool $status = false;

    /**
     * Session constructor.
     */
    protected function __construct()
    {
    }

    /**
     *
     */
    public function start(): void
    {
        if ($this->isStarted()) {
            return;
        }
        $this->status = session_start();
    }

    public function isStarted(): bool
    {
        return $this->status;
    }

    public function regenerate(bool $deleteOld = false): bool
    {
        if (!$this->isStarted()) {
            return false;
        }
        session_regenerate_id($deleteOld);
        return true;
    }

    public function setConfig(string $name, mixed $value): void
    {
        ini_set('session.' . $name, $value);
    }

    public function &getContainer(): array
    {
        return $_SESSION;
    }

    public function getSID(): string|bool|null
    {
        if (!$this->isStarted()) {
            return null;
        }
        return session_id();
    }
}
