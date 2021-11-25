<?php

namespace Ufl\Render;

/**
 * Interface IRender
 * @package Ufl\Render
 */
interface IRender
{

    /**
     * @param array $configs
     * @return void
     */
    public function setConfigs($configs);

    /**
     * @param string|array $name
     * @param mixed $var
     * @param bool $noCache
     * @return static
     */
    public function assign($name, $var = null, $noCache = false);

    /**
     * @param string $template
     * @return string
     */
    public function compile($template);

    /**
     * @param $template
     * @return bool
     */
    public function templateExists($template);

    /**
     * @param string $layout is template path
     * @return void
     */
    public function setLayoutPath($layout);

    /**
     * @return string
     */
    public function getLayoutPath();

    /**
     * @return bool
     */
    public function isLayoutMode();

    /**
     * @param $contentName
     * @return void
     */
    public function setContentName($contentName);

    /**
     * @return string
     */
    public function getContentName();
}