<?php
namespace AnySys;

class Session
{
    /** @var static */
    protected static $instance = null;

    protected $status = false;

    /**
     * Session constructor.
     */
    protected function __construct()
    {
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
     * @return bool
     */
    public function isStarted() {
        return $this->status;
    }

    public function start() {
        if ($this->isStarted()) {
            return;
        }
        session_start();
        $this->status = true;
    }

    /**
     * @param bool $deleteOld
     * @return bool
     */
    public function regenerate($deleteOld = false) {
        if (!$this->isStarted()) {
            return false;
        }
        session_regenerate_id($deleteOld);
        return true;
    }

    /**
     * @param string $name
     * @param string $value
     */
    public function set($name, $value) {
        if ($this->isStarted()) {
            ArrayUtil::set($_SESSION, $name, $value);
        }
    }

    /**
     * @param $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name, $default = null) {
        if ($this->isStarted()) {
            return ArrayUtil::get($_SESSION, $name, $default);
        }
        return $default;
    }

    /**
     * @param string $name
     * @param string|int $value
     */
    public function setConfig($name, $value) {
        ini_set('session.'.$name, $value);
    }
}