<?php

namespace UflAs;

/**
 * Class Config
 * @package UflAs
 */
class Config
{
    /** @var static */
    protected static $instances = array();
    /** @var array */
    protected $configs = array();
    /** @var string */
    protected $configPath = '';

    /**
     * Config constructor.
     * @param string $configPath
     */
    protected function __construct($configPath)
    {
        $this->initConfig($configPath);
    }

    /**
     * initialize configuration
     * @param string $configPath is optional default: __DIR__/../../configs/default.json or {SERVER_ENV}.json
     * @return bool
     */
    public function initConfig($configPath = null)
    {
        if (is_null($configPath) && defined('CONF_PATH')) {
            $configPath = CONF_PATH;
        } elseif (!file_exists($configPath)) {
            $configPath = System::path() . DIRECTORY_SEPARATOR . 'configs';
        }

        if (is_null($configPath) || !file_exists($configPath)) {
            return false;
        }

        if (is_dir($configPath)) {
            $serverEnv = $this->getEnv('SERVER_ENV');
            $configPath .= DIRECTORY_SEPARATOR . ($serverEnv ?: 'default') . '.json';
        }

        if (!file_exists($configPath)) {
            return false;
        }

        $this->configPath = $configPath;
        $this->configs = json_decode(file_get_contents($this->configPath), true);
        return true;
    }

    /**
     * @param string $envName
     * @return string|null
     */
    private function getEnv($envName)
    {
        $env = getenv($envName);
        if ($env === '') {
            return null;
        }
        return strtolower($env);
    }

    /**
     * @param string $store
     * @param null $configPath
     * @return static
     */
    public static function getInstance($store = '_', $configPath = null)
    {
        if (!(isset(self::$instances[$store]) && self::$instances[$store] instanceof static)) {
            self::$instances[$store] = new static($configPath);
        }
        return self::$instances[$store];
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