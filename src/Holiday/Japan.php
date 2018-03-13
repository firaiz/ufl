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
            array('date' => Date::toDate($year, 2, 11, $format), 'name' => '建国記念の日', 'since' => 1967),
            array('date' => Date::toDate($year, 2, 23, $format), 'name' => '天皇誕生日', 'since' => 2020),
            array('date' => Date::toDate($year, 3, static::getSpringEquinoxDay($year), $format), 'name' => '春分の日'),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => '天皇誕生日', 'abort' => 1988),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => 'みどりの日', 'since' => 1989, 'abort' => 2006),
            array('date' => Date::toDate($year, 4, 29, $format), 'name' => '昭和の日', 'since' => 2007),
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
            array('date' => Date::toDate($year, 10, 10, $format), 'name' => '体育の日', 'since' => 1966, 'abort' => 1999),
            array('date' => Date::toDate($year, 11, 3, $format), 'name' => '文化の日'),
            array('date' => Date::toDate($year, 11, 23, $format), 'name' => '勤労感謝の日'),
            array('date' => Date::toDate($year, 12, 23, $format), 'name' => '天皇誕生日', 'since' => 1989, 'abort' => 2019),

            array('date' => Date::toDate($year, 4, 10, $format), 'name' => '明仁親王の結婚の儀', 'since' => 1959, 'abort' => 1959),
            array('date' => Date::toDate($year, 2, 24, $format), 'name' => '昭和天皇の大喪の礼', 'since' => 1989, 'abort' => 1989),
            array('date' => Date::toDate($year, 11, 12, $format), 'name' => '即位の礼正殿の儀', 'since' => 1990, 'abort' => 1990),
            array('date' => Date::toDate($year, 6, 9, $format), 'name' => '皇太子徳仁親王の結婚の儀', 'since' => 1993, 'abort' => 1993),
        );
        /** @var Holiday[] $newHolidays */
        $newHolidays = array();
        foreach ($list as $holiday) {
            if (
                isset($holiday['since']) && $year < $holiday['since'] ||
                isset($holiday['abort']) && $holiday['abort'] < $year
            ) {
                continue;
            }
            $day = Date::object($holiday['date']);
            $newHolidays[$day->format($format)] = static::init($holiday['name'], $day);
        }

        // 国民の休日
        if (1988 <= $year) {
            $prevHoliday = null;
            foreach ($newHolidays as $holiday) {
                if ($prevHoliday instanceof DateTime) {
                    $diff = Date::diffDate($holiday->getDate(), $prevHoliday);
                    if ($diff->days === 2) {
                        $prevHoliday->modify('+1 day');
                        $newHolidays[$prevHoliday->format($format)] = static::init('国民の休日', $prevHoliday);
                    }
                }
                $prevHoliday = clone $holiday->getDate();
            }
        }

        // 振替休日
        $isTransferDateFlag = false;
        $startSubstituteHoliday = Date::toDate(1973, 4, 1);
        foreach ($newHolidays as $holiday) {
            $day = $holiday->getDate();
            if ($day < $startSubstituteHoliday) {
                continue;
            }
            if ($day->format('w') == 0) {
                $isTransferDateFlag = true;
            }

            if ($isTransferDateFlag) {
                $nextDay = clone $day;
                $nextDay->modify('+1 day');
                if (!isset($newHolidays[$nextDay->format($format)])) {
                    $newHolidays[$nextDay->format($format)] = static::init('振替休日', $nextDay);
                    $isTransferDateFlag = false;
                }
            }

        }

        ksort($newHolidays);
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