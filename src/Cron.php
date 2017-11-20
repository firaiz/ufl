<?php

namespace UflAs;

abstract class Cron implements ICron
{
    /**
     * @var Database
     */
    protected $db;

    final public function __construct()
    {
        $this->init();
    }

    protected function init()
    {
        $this->db = Database::getInstance();
    }
}