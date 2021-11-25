<?php

namespace Ufl;

use Ufl\Cron\ICron;

/**
 * Class Cron
 * @package Ufl
 */
abstract class Cron implements ICron
{
    /**
     * @var Database
     */
    protected $db;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @var Request
     */
    protected $request;

    final public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->db = Database::getInstance();
        $this->config = Config::getInstance();
        $this->request = new Request();
    }
}