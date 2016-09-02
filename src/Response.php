<?php
namespace UflAs;

use UflAs\ViewCompiler\ICompiler;

class Response
{
    /** @var Response */
    protected static $_instance;
    /** @var ICompiler */
    protected $compiler;
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
        $this->initCompiler($render['engine']);
        $this->setHeaders($render['headers']);
        $this->setConfigs($render['config']);
    }

    /**
     * @param string $engine is class name. Have a implemented ICompiler interface
     */
    protected function initCompiler($engine)
    {
        $compiler = __NAMESPACE__ . '\\ViewCompiler\\' . $engine . 'Compiler';
        $this->compiler = new $compiler();
    }

    /**
     * @param array $headers
     * @param boolean $isOverwrite
     */
    public function setHeaders($headers, $isOverwrite = false)
    {
        foreach ($headers as $name => $value) {
            $namedValues = array_key_exists($name, $this->headers) ? $this->headers[$name] : array();
            foreach ((array)$value as $val) {
                $namedValues[] = $val;
            }
            $this->headers[$name] = $isOverwrite ? array($value) : $namedValues;
        }
    }

    /**
     * @param array $configs
     * @return void
     */
    protected function setConfigs(array $configs)
    {
        $this->getCompiler()->setConfigs($configs);
    }

    /**
     * @return ICompiler
     */
    protected function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!(static::$_instance instanceof static)) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    /**
     * @param string $templatePath
     */
    public function setLayout($templatePath)
    {
        $this->getCompiler()->setLayoutPath($templatePath);
    }

    /**
     * @param string|array $name
     * @param mixed $var
     * @param boolean $noCache
     * @return static
     */
    function assign($name, $var = null, $noCache = false)
    {
        $this->getCompiler()->assign($name, $var, $noCache);
        return $this;
    }

    /**
     * @param string $template
     */
    public function response($template)
    {
        $this->renderHeaders();
        echo $this->compile($template);
    }

    /**
     * header 出力
     */
    protected function renderHeaders()
    {
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    /**
     * @param string $template
     * @return string
     */
    public function compile($template)
    {
        return $this->getCompiler()->compile($template);
    }

    /**
     * @param mixed $data
     * @param string $charset
     * @return string
     */
    public function responseJson($data, $charset = 'utf-8')
    {
        $this->setHeaders(array('Content-Type' => 'application/json; charset=' . $charset), true);
        $this->renderHeaders();
        echo json_encode($data);
    }

    /**
     * @param mixed $contents is filepath or raw contents or template path
     * @param string $downloadFileName is local file name
     * @param string $contentType
     */
    public function responseDownload($contents, $downloadFileName, $contentType = 'application/octet-stream')
    {
        $isFile = file_exists($contents) && is_readable($contents) && is_file($contents);

        if ($isFile) {
            $size = filesize($contents);
        } else {
            if ($this->getCompiler()->templateExists($contents)) {
                $contents = $this->compile($contents);
            }
            $size = strlen($contents);
        }

        $this->setHeaders(array(
            'Content-Disposition' => 'attachment; filename="' . $downloadFileName . '"',
            'Content-Length' => $size,
            'Content-Type' => $contentType
        ), true);

        $this->renderHeaders();
        if ($isFile) {
            readfile($contents);
        } else {
            echo $contents;
        }
    }
}