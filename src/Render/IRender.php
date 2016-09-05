<?php
namespace UflAs\Render;

interface IRender
{

    /**
     * @param array $configs
     * @return void
     */
    function setConfigs(array $configs);

    /**
     * @param string|array $name
     * @param mixed $var
     * @param boolean $noCache
     * @return static
     */
    function assign($name, $var = null, $noCache = false);

    /**
     * @param string $template
     * @return string
     */
    function compile($template);

    /**
     * @param $template
     * @return boolean
     */
    function templateExists($template);

    /**
     * @param string $layout is template path
     * @return void
     */
    function setLayoutPath($layout);

    /**
     * @return string
     */
    function getLayoutPath();

    /**
     * @return boolean
     */
    function isLayoutMode();

    /**
     * @param $contentName
     * @return void
     */
    function setContentName($contentName);

    /**
     * @return string
     */
    function getContentName();
}