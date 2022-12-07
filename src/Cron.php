<?php

namespace Firaiz\Ufl;

use Firaiz\Ufl\Cron\ICron;

/**
 * Class Cron
 * @package Firaiz\Ufl
 */
abstract class Cron implements ICron
{
    /**
     * @var ?Database
     */
    protected ?Database $db;

    /**
     * @var ?Config
     */
    protected ?Config $config;

    /**
     * @var ?Request
     */
    protected ?Request $request;

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