<?php

namespace UflAs\Router;

/**
 * Interface IRouterContainer
 * @package UflAs\Router
 */
interface IRouterContainer
{
    /**
     * @return void
     */
    public function exec();

    /**
     * @return bool
     */
    public function isValid();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @param array $params
     */
    public function setParams($params);
}