<?php
namespace AnySys;

class Config
{
    /** @var static */
    protected static $instance = null;
    protected $configs = array();
    protected $configPath;

    /**
     * Config constructor.
     */
    protected function __construct()
    {
        $this->initConfig();
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
        } else {
            if (!file_exists($confPath)) {
                $confPath = Storage::getInstance()->getPath('configs');
            }
        }

        if (is_null($confPath) || !file_exists($confPath)) {
            return false;
        }


        if (is_dir($confPath)) {
            $serverEnv = $this->getEnv('SERV_ENV') ?: $this->getEnv('SERVER_ENV');
            $confPath .= DIRECTORY_SEPARATOR . ($serverEnv ?: 'default') . '.json';
        }

        if (!file_exists($confPath)) {
            return false;
        }

        $this->configPath = $confPath;
        $this->configs = json_decode(file_get_contents($this->configPath), true);
        return true;
    }

    private function getEnv($envName) {
        $env = getenv($envName);
        if (strlen($env) === 0) {
            return null;
        }
        return strtolower($env);
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
    public function get($key, $default = null)
    {
        return ArrayUtil::get($this->configs, $key, $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return ArrayUtil::has($this->configs, $key);
    }
}