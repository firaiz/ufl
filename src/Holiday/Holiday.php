<?php

namespace Firaiz\Ufl\Holiday;

use DateTime;

/**
 * Class Holiday
 * @package Firaiz\Ufl\Holiday
 */
abstract class Holiday implements IHoliday
{
    /** @var string */
    protected string $name;
    /** @var DateTime */
    protected DateTime $date;

    /**
     * Holiday constructor.
     * @param string $name
     * @param DateTime $date
     */
    public function __construct(string $name, DateTime $date)
    {
        $this->name = $name;
        $this->date = $date;
    }

    /**
     * @param string $name
     * @param DateTime $date
     * @return static
     */
    protected static function init(string $name, DateTime $date): static
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string|null $format
     * @return DateTime|string
     */
    public function getDate(string $format = null): DateTime|string
    {
        if (is_null($format)) {
            return $this->date;
        }
        return $this->date->format($format);
    }
}