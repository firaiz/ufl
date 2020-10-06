<?php

namespace UflAs;

/**
 * Class Header
 * @package UflAs
 */
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
        if (!(static::$instance instanceof self)) {
            static::$instance = new self();
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
        http_response_code($code);
    }

    private function setHeaders($headers, $isOverWrite)
    {
        foreach ($headers as $name => $value) {
            $namedValues = ArrayUtil::get($this->headers, $name, array());
            foreach ((array)$value as $val) {
                $namedValues[] = $val;
            }
            $this->headers[$name] = $isOverWrite ? (is_array($value) ? $value : array($value)) : $namedValues;
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

    /**
     * clear headers
     */
    public function reset()
    {
        $this->headers = array();
    }

    /**
     * @return bool
     */
    public function isSent()
    {
        return headers_sent();
    }

    /**
     * @param $url
     * @param int $code
     */
    public function location($url, $code = 302)
    {
        if (!$this->isSent()) {
            header('Location: ' . $url, true, $code);
        } else {
            echo '<script type="text/javascript">',
                'window.location.replace="' . $url . '";',
            '</script>',
            '<noscript>',
            '<meta http-equiv="refresh" content="', ($code === 301 ? 0 : 3), ';url=', $url, '" />',
            '</noscript>',
            '<a href="', $url, '">moved page</a>';
        }
        exit;
    }
}