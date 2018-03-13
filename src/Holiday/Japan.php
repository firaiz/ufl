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
    public  static function getSpringEquinoxDay($year)
    {
        return floor(20.8431 + 0.242194 * ($year - 1980)) - floor(($year - 1980)/4);
    }

    /**
     * get autumnal equinox day
     * @param int $year
     * @return int
     */
    public static function getAutumnalEquinoxDay($year)
    {
        return floor(23.2488 + 0.242194 * ($year - 1980)) - floor(($year - 1980)/4);
    }
}