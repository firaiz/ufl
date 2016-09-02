<?php
namespace UflAs\Holiday;

use DateTime;
use UflAs\Date;

class Japan extends Holiday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf($year)
    {
        $format = 'Y-m-d';
        $list = array(
            Date::toDate($year, 1, 1, $format) => '元旦',
            Date::toDate($year, 1, Date::calcWeekDay($year, 1, 2, 1), $format) => '成人の日',
            Date::toDate($year, 2, 11, $format) => '建国記念日',
            Date::toDate($year, 3, static::getSpringEquinoxDay($year), $format) => '春分の日',
            Date::toDate($year, 4, 29, $format) => '昭和の日',
            Date::toDate($year, 5, 3, $format) => '憲法記念日',
            Date::toDate($year, 5, 4, $format) => 'みどりの日',
            Date::toDate($year, 5, 5, $format) => 'こどもの日',
            Date::toDate($year, 7, Date::calcWeekDay($year, 7, 3, 1), $format) => '海の日',
            Date::toDate($year, 8, 11, $format) => '山の日',
            Date::toDate($year, 9, Date::calcWeekDay($year, 9, 3, 1), $format) => '敬老の日',
            Date::toDate($year, 9, static::getAutumnalEquinoxDay($year), $format) => '秋分の日',
            Date::toDate($year, 10, Date::calcWeekDay($year, 10, 2, 1), $format) => '体育の日',
            Date::toDate($year, 11, 3, $format) => '文化の日',
            Date::toDate($year, 11, 23, $format) => '勤労感謝の日',
            Date::toDate($year, 12, 23, $format) => '天皇誕生日',
        );
        $newHolidays = array();
        $prevHoliday = null;
        $isTransferDateFlag = false;
        foreach ($list as $date => $holiday) {
            $day = Date::object($date);
            if ($prevHoliday instanceof DateTime) {
                $diff = Date::diffDate($day, $prevHoliday);
                if ($diff->days === 2) {
                    $prevHoliday->modify('+1 day');
                    $newHolidays[$prevHoliday->format($format)] = static::init('国民の休日', $prevHoliday);
                }
            }
            $newHolidays[$day->format($format)] = static::init($holiday, $day);
            if ($day->format('w') == 0) {
                $isTransferDateFlag = true;
            }
            if ($isTransferDateFlag) {
                $nextDay = clone $day;
                $nextDay->modify('+1 day');
                if (!isset($list[$nextDay->format($format)])) {
                    $newHolidays[$nextDay->format($format)] = static::init('振替休日', $nextDay);
                    $isTransferDateFlag = false;
                }
            }
            $prevHoliday = $day;
        }
        return $newHolidays;
    }

    /**
     * get spring equinox day
     * @param int $year
     * @return int
     */
    private static function getSpringEquinoxDay($year)
    {
        switch ($year % 4) {
            case 0:
                if (2092 <= $year && $year <= 2096) {
                    return 19;
                } elseif (1960 <= $year && $year <= 2088) {
                    return 20;
                } elseif (1900 <= $year && $year <= 1956) {
                    return 21;
                }
                break;
            case 1:
                if (1993 <= $year && $year <= 2097) {
                    return 20;
                } elseif (1901 <= $year && $year <= 1989) {
                    return 21;
                }
                break;
            case 2:
                if (2026 <= $year && $year <= 2098) {
                    return 20;
                } elseif (1902 <= $year && $year <= 2022) {
                    return 21;
                }
                break;
            case 3:
                if (2059 <= $year && $year <= 2099) {
                    return 19;
                } elseif (1927 <= $year && $year <= 2055) {
                    return 20;
                } elseif (1903 <= $year && $year <= 1923) {
                    return 21;
                }
                break;
        }
        return 1000;
    }

    /**
     * get autumnal equinox day
     * @param int $year
     * @return int
     */
    private static function getAutumnalEquinoxDay($year)
    {
        switch ($year % 4) {
            case 0:
                if (2012 <= $year && $year <= 2096) {
                    return 22;
                } elseif (1900 <= $year && $year <= 2008) {
                    return 23;
                }
                break;
            case 1:
                if (2045 <= $year && $year <= 2097) {
                    return 22;
                } elseif (1921 <= $year && $year <= 2041) {
                    return 23;
                } elseif (1901 <= $year && $year <= 1917) {
                    return 24;
                }
                break;
            case 2:
                if (2078 <= $year && $year <= 2098) {
                    return 22;
                } elseif (1950 <= $year && $year <= 2074) {
                    return 23;
                } elseif (1902 <= $year && $year <= 1946) {
                    return 24;
                }
                break;
            case 3:
                if (1983 <= $year && $year <= 2099) {
                    return 23;
                } elseif (1903 <= $year && $year <= 1979) {
                    return 24;
                }
                break;
        }
        return 1000;
    }
}