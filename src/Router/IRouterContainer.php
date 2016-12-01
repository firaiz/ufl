<?php
namespace UflAs\Router;

interface IRouterContainer
{
    /**
     * @return void
     */
    public function exec();

    /**
     * @return boolean
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