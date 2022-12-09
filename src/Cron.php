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
    protected ?Database $db = null;

    /**
     * @var ?Config
     */
    protected ?Config $config = null;

    /**
     * @var ?Request
     */
    protected ?Request $request = null;

    final public function __construct()
    {
        $this->init();
    }

    protected function init():void
    {
        $this->db = Database::getInstance();
        $this->config = Config::getInstance();
        $this->request = new Request();
    }
}