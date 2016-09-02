<?php
namespace UflAs;

class Request
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';
    const TYPE_CLI = 'CLI';
    const TYPE_REQUEST = 'REQUEST';

    protected $requestMethod = null;
    protected $vars = array();
    protected $defaultDetectOrders = array(
        self::TYPE_POST => true,
        self::TYPE_GET => true,
        self::TYPE_REQUEST => true,
    );
    protected $detectOrders;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->detectOrders = $this->defaultDetectOrders;
        $this->vars = array(
            self::TYPE_GET => $_GET,
            self::TYPE_POST => $_POST,
            self::TYPE_REQUEST => $_REQUEST,
        );

        if ($this->isCLIRequest()) {
            $this->detectOrders = array(self::TYPE_CLI => true);
            $this->vars = array(
                self::TYPE_CLI => new CommandLine(),
            );
        }
    }

    /**
     * request is cli
     * @return bool
     */
    protected function isCLIRequest()
    {
        return $this->detectRequest() === self::TYPE_CLI;
    }

    /**
     * detect request method
     * @return string
     */
    private function detectRequest()
    {
        if (!isset($_SERVER["REQUEST_METHOD"])) {
            return self::TYPE_CLI;
        }
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);
        if (!array_key_exists($method, $this->detectOrders)) {
            return null;
        }
        return $method;
    }

    /**
     * @param array $order key => bool array
     * @return bool
     */
    public function setDetectOrder($order)
    {
        if (!is_array($order)) {
            return false;
        }

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
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null)
    {
        return $this->val(self::TYPE_POST, $key, $default);
    }

    /**
     * @param string|string[] $targetTypes
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function val($targetTypes, $key, $default)
    {
        if (is_string($targetTypes)) {
            $targetTypes = array($targetTypes => true);
        }
        foreach ($targetTypes as $type => $null) {
            $val = ArrayUtil::get($this->getTypeVars($type), $key, null);
            if (!is_null($val)) {
                return $val;
            }
        }
        return $default;
    }

    /**
     * @param $type
     * @return array is typed arrays
     */
    private function getTypeVars($type)
    {
        return $this->vars[$type];
    }

    /**
     * get the cli options
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function cli($key, $default = null)
    {
        return $this->val(self::TYPE_CLI, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function both($key, $default = null)
    {
        return $this->val($this->detectOrders, $key, $default);
    }

    /**
     * get the get request value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->val(self::TYPE_GET, $key, $default);
    }
}