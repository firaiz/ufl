<?php

namespace Firaiz\Ufl\Router;

use Firaiz\Ufl\Exception\Route\NotFound;

/**
 * Class AbstractRouter
 * @package Firaiz\Ufl\Router
 */
abstract class AbstractRouter implements IRouter
{
    /**
     * uri path separator
     */
    public const PATH_SEPARATOR = '/';

    /**
     * @var ?string
     */
    private ?string $pathInfo = null;

    /**
     * @var ?IRouterContainer
     */
    private ?IRouterContainer $noRoute = null;

    /**
     * @return string
     */
    public function getPathInfo(): string
    {
        if (is_null($this->pathInfo)) {
            if (isset($_SERVER['REDIRECT_PATH_INFO'])) {
                $_SERVER['PATH_INFO'] = $_SERVER['REDIRECT_PATH_INFO'];
            }
            $selfUri = str_replace(array($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR), array('', '/'), dirname($_SERVER['SCRIPT_FILENAME']));
            $this->pathInfo = str_replace($selfUri, '', $_SERVER['PATH_INFO'] ?? '');
            $this->pathInfo = preg_replace('/(\S+)\/$/', '$1', $this->pathInfo);
        }
        return $this->pathInfo;
    }

    /**
     * @return IRouterContainer
     */
    public function getContainer(): IRouterContainer
    {
        [$context, $params] = $this->makeContextWithParams();
        return $this->initContainer($context, $params);
    }

    /**
     * @return array [context,params]
     */
    abstract protected function makeContextWithParams(): array;

    /**
     * @param mixed $context
     * @param mixed $params
     * @return IRouterContainer
     */
    protected function initContainer(mixed $context, mixed $params): IRouterContainer
    {
        if (!is_array($params)) {
            $params = array();
        }
        return new CallableContainer($context, $params);
    }

    /**
     * @param IRouterContainer $container
     */
    public function setNoRoute(IRouterContainer $container): void
    {
        $this->noRoute = $container;
    }

    /**
     * @return IRouterContainer
     * @throws NotFound
     */
    public function getNoRoute(): IRouterContainer
    {
        if ($this->noRoute instanceof IRouterContainer) {
            $this->noRoute->setParams(array($this->getPathInfo()));
            return $this->noRoute;
        }
        throw new NotFound();
    }
}