<?php
namespace AnySys;

class Request
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';
    const TYPE_CLI = 'CLI';
    const TYPE_REQUEST = 'REQUEST';

    protected $requestMethod = null;
    protected $vars = array();
    protected $defaultDetectOrders = array(
        self::TYPE_POST,
        self::TYPE_GET,
        self::TYPE_REQUEST,
    );
    protected $detectOrders;

    /**
     * Request constructor.
     */
    public function __construct() {
        $this->detectOrders = $this->defaultDetectOrders;
        $this->vars = array(
            self::TYPE_GET => $_GET,
            self::TYPE_POST => $_POST,
            self::TYPE_REQUEST => $_REQUEST,
        );

        if ($this->isCLIRequest()) {
            $this->vars = array(
                self::TYPE_CLI => new CommandLine(),
            );
        }
    }

    /**
     * @param string[] $order
     * @return bool
     */
    public function setDetectOrder($order) {
        if (!is_array($order)) {
            return false;
        }

        $isOk = false;
        foreach ($order as $type) {
            if (in_array($type, $this->defaultDetectOrders)) {
                $isOk = true;
                break;
            }
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
    public function post($key, $default = null) {
        return $this->val(self::TYPE_POST, $key, $default);
    }

    /**
     * get the cli options
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function cli($key, $default = null) {
        return $this->val(self::TYPE_CLI, $key, $default);
    }

    /**
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function both($key, $default = null) {
        return $this->val($this->detectOrders, $key, $default);
    }

    /**
     * get the get request value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $this->val(self::TYPE_GET, $key, $default);
    }

    /**
     * @param $type
     * @return array is typed arrays
     */
    private function getTypeVers($type)
    {
        return $this->vars[$type];
    }

    /**
     * @param string|string[] $targetTypes
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    protected function val($targetTypes, $key, $default) {
        foreach ((array) $targetTypes as $type) {
            $val = ArrayUtil::get($this->getTypeVers($type), $key, null);
            if (!is_null($val)) {
                return $val;
            }
        }
        return $default;
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
        if (!in_array($method, $this->detectOrders)) {
            return null;
        }
        return $method;
    }

    /**
     * request is cli
     * @return bool
     */
    protected function isCLIRequest() {
        return $this->detectRequest() === self::TYPE_CLI;
    }
}