<?php

namespace Ufl;


use Ufl\Traits\SingletonTrait;

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

    /**
     * @return bool
     */
    public function isStarted(): bool
    {
        return $this->status;
    }

    /**
     * @param bool $deleteOld
     * @return bool
     */
    public function regenerate(bool $deleteOld = false): bool
    {
        if (!$this->isStarted()) {
            return false;
        }
        session_regenerate_id($deleteOld);
        return true;
    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function setConfig(string $name, mixed $value): void
    {
        ini_set('session.' . $name, $value);
    }

    /**
     * @return array
     */
    public function &getContainer(): array
    {
        return $_SESSION;
    }

    /**
     * @return string|bool|null
     */
    public function getSID(): string|bool|null
    {
        if (!$this->isStarted()) {
            return null;
        }
        return session_id();
    }
}
