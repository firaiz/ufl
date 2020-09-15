<?php

namespace UflAs\Router;

use UflAs\Exception\Route\NotFound;

/**
 * Class AbstractRouter
 * @package UflAs\Router
 */
abstract class AbstractRouter implements IRouter
{
    /**
     * uri path separator
     */
    const PATH_SEPARATOR = '/';

    /**
     * @var string
     */
    private $pathInfo = null;

    /**
     * @var IRouterContainer
     */
    private $noRoute;

    /**
     * @return string
     */
    public function getPathInfo()
    {
        if (is_null($this->pathInfo)) {
            if (isset($_SERVER['REDIRECT_PATH_INFO'])) {
                $_SERVER['PATH_INFO'] = $_SERVER['REDIRECT_PATH_INFO'];
            }
            $selfUri = str_replace(DIRECTORY_SEPARATOR, '/',
                str_replace($_SERVER['DOCUMENT_ROOT'], '', dirname($_SERVER['SCRIPT_FILENAME'])));
            $this->pathInfo = str_replace($selfUri, '', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
            $this->pathInfo = preg_replace('/(\S+)\/$/', '$1', $this->pathInfo);
        }
        return $this->pathInfo;
    }

    /**
     * @param mixed $context
     * @param mixed $params
     * @return IRouterContainer
     */
    abstract function initContainer($context, $params);

    /**
     * @param IRouterContainer $container
     */
    public function setNoRoute($container)
    {
        $this->noRoute = $container;
    }

    /**
     * @return IRouterContainer
     * @throws NotFound
     */
    public function getNoRoute()
    {
        if ($this->noRoute instanceof IRouterContainer) {
            $this->noRoute->setParams(array($this->getPathInfo()));
            return $this->noRoute;
        }
        throw new NotFound();
    }
}