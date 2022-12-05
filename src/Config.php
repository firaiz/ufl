<?php

namespace Ufl;

use JsonException;

/**
 * Class Config
 * @package Ufl
 */
class Config
{
    /** @var static[] */
    protected static array $instances = array();
    /** @var array */
    protected array $configs = [];
    /** @var string */
    protected string $configPath = '';

    /**
     * Config constructor.
     * @param string $configPath
     */
    protected function __construct(string $configPath)
    {
        $this->initConfig($configPath);
    }

    /**
     * initialize configuration
     * @param string|null $configPath is optional default: __DIR__/../../configs/default.json or {SERVER_ENV}.json
     * @return bool
     * @throws JsonException
     */
    public function initConfig(string $configPath = null): bool
    {
        if (is_null($configPath) && defined('CONF_PATH')) {
            /** @noinspection PhpUndefinedConstantInspection */
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
        $this->configs = json_decode(file_get_contents($this->configPath), true, 512, JSON_THROW_ON_ERROR);
        return true;
    }

    /**
     * @param string $envName
     * @return string|null
     */
    private function getEnv(string $envName): ?string
    {
        $env = getenv($envName);
        if ($env === '') {
            return null;
        }
        return strtolower($env);
    }

    /**
     * @param string $store
     * @param ?string $configPath
     * @return static
     */
    public static function getInstance(string $store = '_', ?string $configPath = null): static
    {
        if (!(isset(self::$instances[$store]) && self::$instances[$store] instanceof static)) {
            self::$instances[$store] = new static($configPath);
        }
        return self::$instances[$store];
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return string|false
     */
    public function get(string $key, mixed $default = null): bool|string
    {
        return ArrayUtil::get($this->configs, $key, $default);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return ArrayUtil::has($this->configs, $key);
    }
}