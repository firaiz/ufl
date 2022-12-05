<?php

namespace Firaiz\Ufl\Holiday;

/**
 * Interface IHoliday
 * @package Firaiz\Ufl\Holiday
 */
interface IHoliday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf(int $year): array;
}