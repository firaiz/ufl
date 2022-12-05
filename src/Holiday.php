<?php

namespace Ufl;

use Exception;
use Ufl\Holiday\Holiday as HolidayBase;
use Ufl\Holiday\Japan as JapanHoliday;

/**
 * Class Holiday
 * @package Ufl
 */
class Holiday
{
    /**
     * @param int $year
     * @param string $countryCode
     * @return HolidayBase[]|array
     * @throws Exception
     */
    public static function listOf(int $year, string $countryCode = 'JP'): array
    {
        return match ($countryCode) {
            'JP' => JapanHoliday::listOf($year),
            default => array(),
        };
    }
}