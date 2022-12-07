<?php

namespace Firaiz\Ufl\Render;

use Exception;
use Smarty;
use SmartyException;
use Firaiz\Ufl\ArrayUtil;
use Firaiz\Ufl\Storage;
use Firaiz\Ufl\System;

/**
 * Class SmartyRender
 * @package Firaiz\Ufl\Render
 */
class SmartyRender implements IRender
{
    /** @var ?Smarty */
    protected ?Smarty $smarty = null;
    /** @var ?string */
    protected ?string $layout = '';
    /** @var string */
    protected string $contentName = 'contents';

    /**
     * SmartyRender constructor.
     */
    public function __construct()
    {
        $this->smarty = new Smarty();
    }

    /**
     * @param array $configs
     * @return void
     * @throws SmartyException
     */
    public function setConfigs(array $configs): void
    {
        $storage = Storage::getInstance();

        // general
        $templateDir = System::templatePath();
        $this->smarty->setTemplateDir($templateDir);
        $this->smarty->setAutoLiteral(ArrayUtil::get($configs, 'general.auto-literal', true));

        // debug
        $this->smarty->setDebugging(ArrayUtil::get($configs, 'debug.use', false));
        $this->smarty->debugging_ctrl = ArrayUtil::get($configs, 'debug.use-url', false) ? 'URL' : 'NONE';

        // compile
        $this->smarty->setCompileDir($storage->getPath(ArrayUtil::get($configs, 'compile.dir', 'compile'), true));
        $this->smarty->setForceCompile(ArrayUtil::get($configs, 'compile.forced', false));
        $this->smarty->use_sub_dirs = ArrayUtil::get($configs, 'compile.use-sub-dir', true);

        // cache
        $this->smarty->setCaching(ArrayUtil::get($configs, 'cache.use', true));
        $this->smarty->setCacheDir($storage->getPath(ArrayUtil::get($configs, 'cache.dir', 'cache' . DIRECTORY_SEPARATOR . 'template'), true));
        $this->smarty->setCacheLifetime(ArrayUtil::get($configs, 'cache.lifetime', 60));

        // plugins
        $plugins = array_merge(ArrayUtil::get($configs, 'plugins', []), [
            [ 'type' => 'function', 'name' => 'csrf_token', 'real_name' => 'csrf_token' ],
            [ 'type' => 'function', 'name' => 'uuid', 'real_name' => 'uuid' ],
        ]);
        foreach ($plugins as $plugin) {
            $this->smarty->registerPlugin($plugin['type'], $plugin['name'], $plugin['real_name']);
        }
    }

    /**
     * @param array|string $name
     * @param mixed|null $var
     * @param bool $noCache
     * @return static
     */
    public function assign(array|string $name, mixed $var = null, bool $noCache = false): static
    {
        $this->smarty->assign($name, $var, $noCache);
        return $this;
    }

    /**
     * @param string $template
     * @return string
     * @throws Exception
     * @throws SmartyException
     */
    public function compile(string $template): string
    {
        if ($this->isLayoutMode()) {
            $this->smarty->assign($this->getContentName(), $template);
            return $this->smarty->fetch($this->getLayoutPath());
        }
        return $this->smarty->fetch($template);
    }

    /**
     * @return bool
     */
    public function isLayoutMode(): bool
    {
        return is_string($this->layout);
    }

    /**
     * @return string|null
     */
    public function getContentName(): ?string
    {
        return $this->isLayoutMode() ? $this->contentName : null;
    }

    /**
     * @param $contentName
     * @return void
     */
    public function setContentName($contentName): void
    {
        $this->contentName = $contentName;
    }

    /**
     * @return ?string
     */
    public function getLayoutPath(): ?string
    {
        return $this->layout;
    }

    /**
     * @param string $template
     * @return bool
     * @throws SmartyException
     */
    public function templateExists($template): bool
    {
        return $this->smarty->templateExists($template);
    }

    /**
     * @param ?string $layout is template path
     * @return void
     */
    public function setLayoutPath(?string $layout): void
    {
        $this->layout = $layout;
    }
}