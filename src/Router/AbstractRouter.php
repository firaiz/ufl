<?php
namespace UflAs\Router;

use UflAs\Exception\Route\NotFound;

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
            $selfUri = str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), dirname($_SERVER['SCRIPT_FILENAME']));
            $this->pathInfo = str_replace($selfUri, '', isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');
            $this->pathInfo = preg_replace('/(\S+)\/$/', '$1', $this->pathInfo);
        }
        return $this->pathInfo;
    }

    /**
     * @return IRouterContainer
     */
    public function getContainer()
    {
        list($context, $params) = $this->makeContextWithParams();
        return $this->initContainer($context, $params);
    }

    /**
     * @return array [context,params]
     */
    abstract protected function makeContextWithParams();

    /**
     * @param mixed $context
     * @param mixed $params
     * @return IRouterContainer
     */
    protected function initContainer($context, $params)
    {
        if (!is_array($params)) {
            $params = array();
        }
        return new CallableContainer($context, $params);
    }

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