<?php

namespace UflAs\Holiday;

use DateTime;

/**
 * Class Holiday
 * @package UflAs\Holiday
 */
abstract class Holiday implements IHoliday
{
    /** @var string */
    protected $name;
    /** @var DateTime */
    protected $date;

    /**
     * Holiday constructor.
     * @param string $name
     * @param DateTime $date
     */
    public function __construct($name, DateTime $date)
    {
        $this->name = $name;
        $this->date = $date;
    }

    /**
     * @param string $name
     * @param DateTime $date
     * @return static
     */
    protected static function init($name, $date)
    {
        return new static($name, $date);
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string [$format]
     * @return DateTime|string
     */
    public function getDate($format = null)
    {
        if (is_null($format)) {
            return $this->date;
        }
        return $this->date->format($format);
    }
}