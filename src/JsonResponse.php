<?php

namespace UflAs;

class JsonResponse extends Response
{
    private $assigns = array();

    public function assign($name, $var = null, $noCache = false)
    {
        $this->assigns[$name] = $var;
    }

    public function write($charset = null) {
        $this->json($this->assigns, $charset);
    }
}