<?php
namespace Ays;

class Config
{
    protected $configs = array();
    protected $configPath;

    /** @var static */
    protected static $instance = null;

    protected function __construct() {
        $this->initConfig();
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!(self::$instance instanceof static)) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return string|false
     */
    public function get($key, $default = null) {
        return ArrayUtil::get($this->configs, $key, $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key) {
        return ArrayUtil::has($this->configs, $key);
    }

    /**
     * initialize configuration
     * @param string $confPath is optional default: __DIR__/../../configs/default.json or {SERV_ENV}.json
     * @return bool
     */
    public function initConfig($confPath = null)
    {

        if (is_null($confPath) && defined('CONF_PATH')) {
            $confPath = CONF_PATH;
        } else if (!file_exists($confPath)) {
            $confPath = Storage::getInstance()->getPath('configs');
        }

        if (is_null($confPath) || !file_exists($confPath)) {
            return false;
        }


        if (is_dir($confPath)) {
            $confPath .= DIRECTORY_SEPARATOR.(isset($_ENV['SERV_ENV']) ? strtolower($_ENV['SERV_ENV']) : 'default').'.json';
        }

        if (!file_exists($confPath)) {
            return false;
        }

        $this->configPath = $confPath;
        $this->configs = json_decode(file_get_contents($this->configPath), true);
        return true;
    }
}