<?php

namespace Ufl;

use JsonException;
use stdClass;

class Request
{
    public const TYPE_GET = 'GET';
    public const TYPE_POST = 'POST';
    public const TYPE_CLI = 'CLI';
    public const TYPE_PUT = 'PUT';
    public const TYPE_DELETE = 'DELETE';
    public const TYPE_OPTIONS = 'OPTIONS';
    public const TYPE_REQUEST = 'REQUEST';

    protected array $vars = array();
    protected array $defaultDetectOrders = array(
        self::TYPE_POST => true,
        self::TYPE_GET => true,
        self::TYPE_REQUEST => true,
    );
    protected array $detectOrders;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->detectOrders = $this->defaultDetectOrders;
        $parseInputValues = array();
        @parse_str($this->input(), $parseInputValues);
        $this->vars = array(
            self::TYPE_GET => $_GET,
            self::TYPE_POST => $_POST,
            self::TYPE_REQUEST => $_REQUEST,
            self::TYPE_PUT => $parseInputValues,
            self::TYPE_DELETE => $parseInputValues,
            self::TYPE_OPTIONS => $parseInputValues,
        );

        if ($this->is(self::TYPE_CLI)) {
            $this->detectOrders = array(self::TYPE_CLI => true);
            $this->vars = array(
                self::TYPE_CLI => new CommandLine(),
            );
        }
    }

    /**
     * @param string $requestType
     * @return bool
     */
    public function is(string $requestType): bool
    {
        return $this->detectRequest() === $requestType;
    }

    /**
     * @return false|string
     */
    public function input(): bool|string
    {
        return file_get_contents('php://input');
    }

    /**
     * @param bool $toArray
     * @return stdClass|array
     * @throws JsonException
     */
    public function json(bool $toArray = false): array|stdClass
    {
        return json_decode($this->input(), $toArray, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * detect request method
     * @return string
     */
    private function detectRequest(): string
    {
        if (!isset($_SERVER["REQUEST_METHOD"])) {
            return self::TYPE_CLI;
        }
        return strtoupper($_SERVER["REQUEST_METHOD"]);
    }

    /**
     * @param array $order key => bool array
     * @return bool
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
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function post(string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_POST, $key, $default);
    }

    /**
     * @param string|string[] $targetTypes
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function val(array|string $targetTypes, string $key, mixed $default): mixed
    {
        if (is_string($targetTypes)) {
            $targetTypes = array($targetTypes => true);
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
     * @param int|string $key
     * @param mixed $default
     * @return mixed
     */
    public function cli(int|string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_CLI, $key, $default);
    }

    /**
     * get the get or post method request value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function both(string $key, mixed $default = null): mixed
    {
        return $this->val($this->detectOrders, $key, $default);
    }

    /**
     * get the get request value
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->val(self::TYPE_GET, $key, $default);
    }
}