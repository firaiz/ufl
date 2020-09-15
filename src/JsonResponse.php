<?php

namespace UflAs;

/**
 * Class JsonResponse
 * @package UflAs
 */
class JsonResponse extends Response
{
    private $assigns = array();

    /**
     * @param array|string $name
     * @param mixed $var
     * @param false $noCache
     * @return JsonResponse|void
     */
    public function assign($name, $var = null, $noCache = false)
    {
        $this->assigns[$name] = $var;
    }

    /**
     * @param string $charset
     */
    public function write($charset = null)
    {
        $this->json($this->assigns, $charset);
    }
}