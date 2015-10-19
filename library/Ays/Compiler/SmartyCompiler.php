<?php
namespace Ays\Compiler;

use Ays\ArrayUtil;
use Smarty;

class SmartyCompiler implements ICompiler
{
    protected $smarty;

    function __construct()
    {
        $this->smarty = new Smarty();
    }

    /**
     * @param array $configs
     * @return void
     */
    function setConfigs(array $configs)
    {
        // general
        $this->smarty->setTemplateDir(ArrayUtil::get($configs, 'general.dir'));
        $this->smarty->setAutoLiteral(ArrayUtil::get($configs, 'general.auto-literal'));

        // debug
        $this->smarty->setDebugging(ArrayUtil::get($configs, 'debug.use', false));
        $this->smarty->debugging_ctrl = ArrayUtil::get($configs, 'debug.use-url', false) ? 'URL' : 'NONE';

        // compile
        $this->smarty->setCompileDir(ArrayUtil::get($configs, 'compile.dir'));
        $this->smarty->setForceCompile(ArrayUtil::get($configs, 'compile.forced', false));
        $this->smarty->use_sub_dirs = ArrayUtil::get($configs, 'compile.use-sub-dir', true);

        // cache
        $this->smarty->setCaching(ArrayUtil::get($configs, 'cache.use', true));
        $this->smarty->setCacheDir(ArrayUtil::get($configs, 'cache.dir'));
        $this->smarty->setCacheLifetime(ArrayUtil::get($configs, 'cache.lifetime', 60));
    }

    /**
     * @param string|array $name
     * @param mixed $var
     * @param boolean $noCache
     * @return static
     */
    function assign($name, $var = null, $noCache = false)
    {
        $this->smarty->assign($name, $var, $noCache);
        return $this;
    }

    /**
     * @param string $template
     * @return string
     */
    function compile($template)
    {
        return $this->smarty->fetch($template);
    }

    /**
     * @param string $template
     * @return bool
     */
    function templateExists($template) {
        return $this->smarty->templateExists($template);
    }
}