<?php
namespace Ufl\Exception\Route;

use Exception;

/**
 * Class NotFound
 * @package Ufl\Exception\RouteÒ
 */
class NotFound extends Exception
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct("route is not found", 404, $previous);
    }
}