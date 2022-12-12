<?php

namespace Firaiz\Ufl;

use JsonException;
use stdClass;

class Request
{
    final public const TYPE_GET = 'GET';
    final public const TYPE_POST = 'POST';
    final public const TYPE_CLI = 'CLI';
    final public const TYPE_PUT = 'PUT';
    final public const TYPE_DELETE = 'DELETE';
    final public const TYPE_OPTIONS = 'OPTIONS';
    final public const TYPE_REQUEST = 'REQUEST';

    protected array $vars = [];
    protected array $defaultDetectOrders = [self::TYPE_POST => true, self::TYPE_GET => true, self::TYPE_REQUEST => true];
    protected array $detectOrders;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->detectOrders = $this->defaultDetectOrders;
        $parseInputValues = [];
        @parse_str($this->input(), $parseInputValues);
        $this->vars = [self::TYPE_GET => $_GET, self::TYPE_POST => $_POST, self::TYPE_REQUEST => $_REQUEST, self::TYPE_PUT => $parseInputValues, self::TYPE_DELETE => $parseInputValues, self::TYPE_OPTIONS => $parseInputValues];

        if ($this->is(self::TYPE_CLI)) {
            $this->detectOrders = [self::TYPE_CLI => true];
            $this->vars = [self::TYPE_CLI => new CommandLine()];
        }
    }

    public function is(string $requestType): bool
    {
        return $this->detectRequest() === $requestType;
    }

    public function input(): bool|string
    {
        return file_get_contents('php://input');
    }

    /**
     * @throws JsonException
     */
    public function json(bool $toArray = false): array|stdClass
    {
        return json_decode($this->input(), $toArray, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * detect request method
     */
    private function detectRequest(): string
    {
        if (!isset($_SERVER["REQUEST_METHOD"])) {
            return self::TYPE_CLI;
        }
        return strtoupper((string) $_SERVER["REQUEST_METHOD"]);
    }

    /**
     * @param array $order key => bool array
     */
    public function setDetectOrder(array $order): bool
    {
        $isOk = true;
        foreach ($order as $type) {
            $isOk = $isOk && array_key_exists($type, $this->defaultDetectOrders);
        }
        if ($isOk) {
            $this->detectOrders = $order;
        }
        return $isOk;
    }

    /**
     * get the post request value
     * @param mixed|null $default
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_POST, $key, $default);
    }

    /**
     * @param string|string[] $targetTypes
     */
    protected function val(array|string $targetTypes, string $key, mixed $default): mixed
    {
        if (is_string($targetTypes)) {
            $targetTypes = [$targetTypes => true];
        }
        foreach ($targetTypes as $type => $null) {
            $val = ArrayUtil::get($this->getTypeVars($type), $key);
            if (!is_null($val)) {
                return $val;
            }
        }
        return $default;
    }

    /**
     * @param $type
     * @return array|CommandLine is typed arrays
     */
    private function getTypeVars($type): array|CommandLine
    {
        return $this->vars[$type];
    }

    /**
     * get the cli options
     */
    public function cli(int|string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_CLI, $key, $default);
    }

    /**
     * get the get or post method request value
     */
    public function both(string $key, mixed $default = null): mixed
    {
        return $this->val($this->detectOrders, $key, $default);
    }

    /**
     * get the get request value
     * @param mixed|null $default
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_GET, $key, $default);
    }
}