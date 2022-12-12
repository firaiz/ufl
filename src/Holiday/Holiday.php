<?php

namespace Firaiz\Ufl\Holiday;

use DateTime;
use Stringable;

/**
 * Class Holiday
 * @package Firaiz\Ufl\Holiday
 */
abstract class Holiday implements IHoliday, Stringable
{
    /**
     * Holiday constructor.
     * @param string|null $name
     * @param DateTime|null $date
     */
    public function __construct(protected ?string $name, protected ?DateTime $date)
    {
    }

    /**
     * @return static
     */
    protected static function init(string $name, DateTime $date): static
    {
        return new static($name, $date);
    }

    public function __toString(): string
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