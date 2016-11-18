<?php
/**
 * Created by PhpStorm.
 * User: k.yoshida
 * Date: 2016/11/18
 * Time: 16:22
 */

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
}