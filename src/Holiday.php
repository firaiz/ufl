<?php
namespace UflAs;

use UflAs\Holiday\Japan AS JapanHoliday;

class Holiday
{
    /**
     * @param int $year
     * @param string [$countryCode]
     * @return static[]|array
     */
    public static function listOf($year, $countryCode = 'JP') {
        switch ($countryCode) {
            case 'JP':
            return JapanHoliday::listOf($year);
        }
        return array();
    }
}