<?php

namespace UflAs\Holiday;

/**
 * Interface IHoliday
 * @package UflAs\Holiday
 */
interface IHoliday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf($year);
}