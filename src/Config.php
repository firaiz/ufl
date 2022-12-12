<?php

namespace Firaiz\Ufl;

use JsonException;

/**
 * Class Config
 * @package Firaiz\Ufl
 */
class Config
{
    /** @var static[] */
    protected static array $instances = [];
    /** @var array */
    protected array $configs = [];
    /** @var string */
    protected string $configPath = '';

    /**
     * Config constructor.
     * @throws JsonException
     */
    protected function __construct(?string $configPath)
    {
        $this->initConfig($configPath);
    }

    /**
     * initialize configuration
     * @param string|null $configPath is optional default: __DIR__/../../configs/default.json or {SERVER_ENV}.json
     * @throws JsonException
     */
    public function initConfig(string $configPath = null): bool
    {
        if (is_null($configPath) && defined('CONF_PATH')) {
            /** @noinspection PhpUndefinedConstantInspection */
            $configPath = CONF_PATH;
        } elseif (is_null($configPath) || !file_exists($configPath)) {
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

    private function getEnv(string $envName): ?string
    {
        $env = getenv($envName);
        if ($env === '') {
            return null;
        }
        return strtolower($env);
    }

    /**
     * @throws JsonException
     */
    public static function getInstance(string $store = '_', ?string $configPath = null): static
    {
        if (!(isset(self::$instances[$store]) && self::$instances[$store] instanceof static)) {
            self::$instances[$store] = new static($configPath);
        }
        return self::$instances[$store];
    }

    /**
     * @param mixed|null $default
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return ArrayUtil::get($this->configs, $key, $default);
    }

    public function has(string $key): bool
    {
        return ArrayUtil::has($this->configs, $key);
    }
}