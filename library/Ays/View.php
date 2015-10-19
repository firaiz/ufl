<?php
namespace Ays;

use Ays\Compiler\ICompiler;

class View
{
    /** @var View */
    protected static $_instance;
    /** @var ICompiler */
    protected $compiler;

    protected function __construct()
    {
        $config = Config::getInstance();
        $render = $config->get("render");

        if (isset($render['engine']) === false) {
            $render['engine'] = 'Smarty';
        }
        $this->initCompiler($render['engine']);
        $this->setConfigs($render['config']);
    }

    /**
     * @param string $engine is class name. Have a implemented ICompiler interface
     */
    protected function initCompiler($engine)
    {
        $compiler = __NAMESPACE__ . '\\Compiler\\' . $engine . 'Compiler';
        $this->compiler = new $compiler();
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
    public function render($template)
    {
        echo $this->compile($template);
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
     * @return string
     */
    public function toJSON($data)
    {
        return json_encode($data);
    }

    /**
     * @param mixed $contents is filepath or contents or template path
     * @param string $downloadFileName
     * @param string $contentType
     */
    public function download($contents, $downloadFileName, $contentType = 'application/octet-stream')
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

        header('Content-Disposition: inline; filename="' . $downloadFileName . '"');
        header('Content-Length: ' . $size);
        header('Content-Type: ' . $contentType);

        if ($isFile) {
            readfile($contents);
        } else {
            echo $contents;
        }
    }
}