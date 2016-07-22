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
     *
     */
    public function start()
    {
        if ($this->isStarted()) {
            return;
        }
        $this->status = session_start();
    }

    /**
     * @return bool
     */
    public function isStarted()
    {
        return $this->status;
    }

    /**
     * @param bool $deleteOld
     * @return bool
     */
    public function regenerate($deleteOld = false)
    {
        if (!$this->isStarted()) {
            return false;
        }
        session_regenerate_id($deleteOld);
        return true;
    }

    /**
     * @param string $name
     * @param string|int $value
     */
    public function setConfig($name, $value)
    {
        ini_set('session.' . $name, $value);
    }

    /**
     * @return array
     */
    public function &getContainer()
    {
        return $_SESSION;
    }
}
