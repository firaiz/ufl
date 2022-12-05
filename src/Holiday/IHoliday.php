<?php

namespace Ufl\Holiday;

/**
 * Interface IHoliday
 * @package Ufl\Holiday
 */
interface IHoliday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf(int $year): array;
}