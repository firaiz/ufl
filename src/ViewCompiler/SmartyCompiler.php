<?php
namespace UflAs\ViewCompiler;

use UflAs\ArrayUtil;
use UflAs\Storage;
use Smarty;

class SmartyCompiler implements ICompiler
{
    /** @var Smarty */
    protected $smarty;
    /** @var string */
    protected $layout;
    /** @var string */
    protected $contentName = 'contents';

    /**
     * SmartyCompiler constructor.
     */
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
        $storage = Storage::getInstance();

        // general
        $this->smarty->setTemplateDir($storage->getPath(ArrayUtil::get($configs, 'general.dir'), true));
        $this->smarty->setAutoLiteral(ArrayUtil::get($configs, 'general.auto-literal'));

        // debug
        $this->smarty->setDebugging(ArrayUtil::get($configs, 'debug.use', false));
        $this->smarty->debugging_ctrl = ArrayUtil::get($configs, 'debug.use-url', false) ? 'URL' : 'NONE';

        // compile
        $this->smarty->setCompileDir($storage->getPath(ArrayUtil::get($configs, 'compile.dir'), true));
        $this->smarty->setForceCompile(ArrayUtil::get($configs, 'compile.forced', false));
        $this->smarty->use_sub_dirs = ArrayUtil::get($configs, 'compile.use-sub-dir', true);

        // cache
        $this->smarty->setCaching(ArrayUtil::get($configs, 'cache.use', true));
        $this->smarty->setCacheDir($storage->getPath(ArrayUtil::get($configs, 'cache.dir'), true));
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
        if ($this->isLayoutMode()) {
            $this->smarty->assign($this->getContentName(), $template);
            return $this->smarty->fetch($this->getLayoutPath());
        }
        return $this->smarty->fetch($template);
    }

    /**
     * @return boolean
     */
    function isLayoutMode()
    {
        return is_string($this->layout);
    }

    /**
     * @return string
     */
    function getContentName()
    {
        return $this->isLayoutMode() ? $this->contentName : null;
    }

    /**
     * @param $contentName
     * @return void
     */
    function setContentName($contentName)
    {
        $this->contentName = $contentName;
    }

    /**
     * @return string
     */
    function getLayoutPath()
    {
        return $this->layout;
    }

    /**
     * @param string $template
     * @return bool
     */
    function templateExists($template)
    {
        return $this->smarty->templateExists($template);
    }

    /**
     * @param string $layout is template path
     * @return void
     */
    function setLayoutPath($layout)
    {
        $this->layout = $layout;
    }
}