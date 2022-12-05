<?php

namespace Firaiz\Ufl;

use Exception;
use Firaiz\Ufl\Holiday\Holiday as HolidayBase;
use Firaiz\Ufl\Holiday\Japan as JapanHoliday;

/**
 * Class Holiday
 * @package Firaiz\Ufl
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