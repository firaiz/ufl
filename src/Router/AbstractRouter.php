<?php
namespace UflAs\Router;

use UflAs\Exception\Route\NotFound;

abstract class AbstractRouter implements IRouter
{
    private $pathInfo = null;
    /**
     * @var \Closure
     */
    private $noRoute = null;

    /**
     * @param \Closure $closure
     */
    public function setNoRoute($closure)
    {
        $this->noRoute = $closure;
    }

    /**
     * @throws \UflAs\Exception\Route\NotFound
     */
    protected function notDetectRoute()
    {
        if (!is_callable($this->noRoute)) {
            throw new NotFound();
        }
        call_user_func_array($this->noRoute, array($this->getPathInfo()));
    }

    /**
     * @return string
     */
    public function getPathInfo()
    {
        if (is_null($this->pathInfo)) {
            $selfUri = str_replace(DIRECTORY_SEPARATOR, '/',
                str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME'])));
            $this->pathInfo = str_replace($selfUri, '', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
        }
        return $this->pathInfo;
    }
}