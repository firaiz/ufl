<?php

namespace Ufl;

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
     * @param string [$countryCode]
     * @return HolidayBase[]|array
     */
    public static function listOf($year, $countryCode = 'JP')
    {
        switch ($countryCode) {
            case 'JP':
                return JapanHoliday::listOf($year);
        }
        return array();
    }
}