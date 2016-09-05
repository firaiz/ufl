<?php
namespace UflAs;

use UflAs\Render\IRender;

class Render implements IRender
{
    /** @var Render */
    protected static $instance;
    /** @var IRender */
    protected $render;
    /** @var array */
    protected $headers = array();

    /**
     * View constructor.
     */
    protected function __construct()
    {
        $config = Config::getInstance();
        $render = $config->get("render");

        if (isset($render['engine']) === false) {
            $render['engine'] = 'Smarty';
        }
        $this->initRender($render['engine']);
        $this->setDefaultHeaders($render['headers']);
        $this->setConfigs($render['config']);
    }

    /**
     * @param string $engine is class name. Have a implemented Render interface
     */
    protected function initRender($engine)
    {
        $render = __NAMESPACE__ . '\\Render\\' . $engine . 'Render';
        $this->render = new $render();
    }

    private function setDefaultHeaders($headers)
    {
        $this->headers = $headers;
    }

    /**
     * @param array $configs
     * @return void
     */
    public function setConfigs(array $configs)
    {
        $this->getRender()->setConfigs($configs);
    }

    /**
     * @return IRender
     */
    private function getRender()
    {
        return $this->render;
    }

    public function getDefaultHeaders()
    {
        return $this->headers;
    }

    /**
     * @param string $templatePath
     */
    public function setLayout($templatePath)
    {
        $this->getRender()->setLayoutPath($templatePath);
    }

    /**
     * @param string|array $name
     * @param mixed $var
     * @param boolean $noCache
     * @return static
     */
    function assign($name, $var = null, $noCache = false)
    {
        $this->getRender()->assign($name, $var, $noCache);
        return $this;
    }

    /**
     * @param $template
     * @return string
     */
    public function compile($template)
    {
        return $this->getRender()->compile($template);
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!(static::$instance instanceof static)) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    /**
     * @param $template
     * @return boolean
     */
    function templateExists($template)
    {
        return $this->getRender()->templateExists($template);
    }

    /**
     * @param string $layout is template path
     * @return void
     */
    function setLayoutPath($layout)
    {
        $this->getRender()->setLayoutPath($layout);
    }

    /**
     * @return string
     */
    function getLayoutPath()
    {
        return $this->getRender()->getLayoutPath();
    }

    /**
     * @return boolean
     */
    function isLayoutMode()
    {
        return $this->getRender()->isLayoutMode();
    }

    /**
     * @param $contentName
     * @return void
     */
    function setContentName($contentName)
    {
        $this->getRender()->setContentName($contentName);
    }

    /**
     * @return string
     */
    function getContentName()
    {
        return $this->getRender()->getContentName();
    }
}