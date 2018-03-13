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
            array('date' => Date::toDate($year, 1, 1, $format), 'name' => '元日'),
            array('date' => Date::toDate($year, 1, Date::calcWeekDay($year, 1, 2, 1), $format), 'name' => '成人の日', 'since' => 2000),
            array('date' => Date::toDate($year, 1, 15, $format), 'name' => '成人の日', 'abort' => 1999),
            array('date' => Date::toDate($year, 2, 11, $format), 'name' => '建国記念日', 'since' => 1967),
            array('date' => Date::toDate($year, 3, static::getSpringEquinoxDay($year), $format), 'name' => '春分の日'),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => '天皇誕生日', 'abort' => 1988),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => 'みどりの日', 'since' => 1989, 'abort' => 2006),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => '昭和の日'),
            array('date' => Date::toDate($year, 5, 3, $format), 'name' => '憲法記念日'),
            array('date' => Date::toDate($year, 5, 4, $format), 'name' => 'みどりの日', 'since' => 2007),
            array('date' => Date::toDate($year, 5, 5, $format), 'name' => 'こどもの日'),
            array('date' => Date::toDate($year, 7, Date::calcWeekDay($year, 7, 3, 1), $format), 'name' => '海の日', 'since' => 2003),
            array('date' => Date::toDate($year, 7, 20, $format), 'name' => '海の日', 'since' => 1996, 'abort' => 2002),
            array('date' => Date::toDate($year, 8, 11, $format), 'name' => '山の日', 'since' => 2016),
            array('date' => Date::toDate($year, 9, 15, $format), 'name' => '敬老の日', 'since' => 1966, 'abort' => 2002),
            array('date' => Date::toDate($year, 9, Date::calcWeekDay($year, 9, 3, 1), $format), 'name' => '敬老の日', 'since' => 2003),
            array('date' => Date::toDate($year, 9, static::getAutumnalEquinoxDay($year), $format), 'name' => '秋分の日'),
            array('date' => Date::toDate($year, 10, Date::calcWeekDay($year, 10, 2, 1), $format), 'name' => '体育の日', 'since' => 2000),
            array('date' => Date::toDate($year, 10, 10, $format), 'name' => '体育の日', 'abort' => 1999),
            array('date' => Date::toDate($year, 11, 3, $format), 'name' => '文化の日'),
            array('date' => Date::toDate($year, 11, 23, $format), 'name' => '勤労感謝の日'),
            array('date' => Date::toDate($year, 12, 23, $format), 'name' => '天皇誕生日', 'since' => 1989, 'abort' => 2019),
        );
        $newHolidays = array();
        $prevHoliday = null;
        $isTransferDateFlag = false;
        foreach ($list as $holiday) {
            if (
                isset($holiday['since']) && $year < $holiday['since'] ||
                isset($holiday['abort']) && $holiday['abort'] < $year
            ) {
                continue;
            }
            $day = Date::object($holiday['date']);
            if ($prevHoliday instanceof DateTime) {
                $diff = Date::diffDate($day, $prevHoliday);
                if ($diff->days === 2) {
                    $prevHoliday->modify('+1 day');
                    $newHolidays[$prevHoliday->format($format)] = static::init('国民の休日', $prevHoliday);
                }
            }
            $newHolidays[$day->format($format)] = static::init($holiday['name'], $day);
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
            $prevHoliday = clone $day;
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