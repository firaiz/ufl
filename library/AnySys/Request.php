<?php
namespace AnySys;

class Request
{
    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';
    const TYPE_CLI = 'CLI';

    protected $requestMethod = null;
    protected $vars = array();

    public function __construct() {
        $this->detectRequest();
        $this->vars = array(
            self::TYPE_GET => $_GET,
            self::TYPE_POST => $_POST,
            self::TYPE_CLI => new CommandLine(),
        );
    }

    /**
     * get the post request value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function post($key, $default = null) {
        return ArrayUtil::get($this->getTypeVers(self::TYPE_POST), $key, $default);
    }

    /**
     * get the cli options
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function cli($key, $default = null) {
        return ArrayUtil::get($this->getTypeVers(self::TYPE_CLI), $key, $default);
    }

    /**
     * get the get request value
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return ArrayUtil::get($this->getTypeVers(self::TYPE_GET), $key, $default);
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
     * detect request method
     * @return void
     */
    private function detectRequest()
    {
        if (!isset($_SERVER["REQUEST_METHOD"])) {

            return;
        }
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);
        if (!in_array($method, array('POST','GET'))) {
            return;
        }
        $this->requestMethod = $method;
    }
}