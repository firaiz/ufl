<?php

namespace Firaiz\Ufl;

use JetBrains\PhpStorm\NoReturn;
use Firaiz\Ufl\Traits\SingletonTrait;

/**
 * Class Header
 * @package Firaiz\Ufl
 */
class Header
{
    use SingletonTrait;

    protected array $headers = [];

    /**
     * Header constructor.
     */
    private function __construct()
    {
        // empty
    }

    public function flush(): void
    {
        foreach ($this->headers as $name => $values) {
            foreach ($values as $value) {
                header(sprintf('%s: %s', $name, $value), false);
            }
        }
    }

    public function code(int $code): void
    {
        http_response_code($code);
    }

    private function setHeaders($headers, $isOverWrite): void
    {
        foreach ($headers as $name => $value) {
            $namedValues = ArrayUtil::get($this->headers, $name, []);
            foreach ((array)$value as $val) {
                $namedValues[] = $val;
            }
            $this->headers[$name] = $isOverWrite ? (is_array($value) ? $value : [$value]) : $namedValues;
        }
    }

    public function add(array $headers): void
    {
        $this->setHeaders($headers, false);
    }

    public function set(array $headers): void
    {
        $this->setHeaders($headers, true);
    }

    /**
     * clear headers
     */
    public function reset(): void
    {
        $this->headers = [];
    }

    public function isSent(): bool
    {
        return headers_sent();
    }

    /**
     * @param $url
     * @param int $code
     * @return never
     */
    #[NoReturn] public function location($url, int $code = 302): never
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