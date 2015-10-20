<?php
namespace AnySys\Compiler;

interface ICompiler
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
}