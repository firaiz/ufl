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
    protected Database $db;

    /**
     * @var Config
     */
    protected Config $config;

    /**
     * @var Request
     */
    protected Request $request;

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