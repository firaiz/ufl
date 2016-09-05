<?php
namespace UflAs;

class Header
{
    private static $instance;

    protected $headers = array();

    /**
     * Header constructor.
     */
    private function __construct()
    {
        // empty
    }


    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance instanceof Header) {
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function flush()
    {
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    /**
     * @param int $code
     */
    public function code($code)
    {
        header('', true, $code);
    }

    private function setHeaders($headers, $isOverWrite) {
        foreach ($headers as $name => $value) {
            $namedValues = ArrayUtil::get($this->headers, $name, array());
            foreach ((array)$value as $val) {
                $namedValues[] = $val;
            }
            $this->headers[$name] = $isOverWrite ? array($namedValues) : $namedValues;
        }
    }

    /**
     * @param array $headers
     */
    public function add($headers)
    {
        $this->setHeaders($headers, false);
    }

    /**
     * @param array $headers
     */
    public function set($headers)
    {
        $this->setHeaders($headers, true);
    }

    public function reset() {
        $this->headers = array();
    }
}