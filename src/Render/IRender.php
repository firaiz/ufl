<?php

namespace Firaiz\Ufl\Render;

/**
 * Interface IRender
 * @package Firaiz\Ufl\Render
 */
interface IRender
{

    /**
     * @param array $configs
     * @return void
     */
    public function setConfigs(array $configs): void;

    /**
     * @param array|string $name
     * @param mixed|null $var
     * @param bool $noCache
     * @return static
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static;

    /**
     * @param string $template
     * @return string
     */
    public function compile(string $template): string;

    /**
     * @param $template
     * @return bool
     */
    public function templateExists($template): bool;

    /**
     * @param ?string $layout is template path
     * @return void
     */
    public function setLayoutPath(?string $layout): void;

    /**
     * @return ?string
     */
    public function getLayoutPath(): ?string;

    /**
     * @return bool
     */
    public function isLayoutMode(): bool;

    /**
     * @param $contentName
     * @return void
     */
    public function setContentName($contentName): void;

    /**
     * @return ?string
     */
    public function getContentName(): ?string;
}