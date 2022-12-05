<?php

namespace Ufl\Holiday;

use DateTime;
use Exception;
use Ufl\Date;

/**
 * Class Japan
 * @package Ufl\Holiday
 */
class Japan extends Holiday
{
    public const FORMAT = 'Y-m-d';
    public const CHECK_TYPE_ABORT = 'abort';
    public const CHECK_TYPE_SINCE = 'since';

    public static function getYearConfig($year): array
    {
        return array(
            array('date' => Date::toDate($year, 1, 1, self::FORMAT), 'name' => '元日'),
            array('date' => Date::toDate($year, 1, Date::calcWeekDay($year, 1, 2, 1), self::FORMAT), 'name' => '成人の日', self::CHECK_TYPE_SINCE => 2000),
            array('date' => Date::toDate($year, 2, 11, self::FORMAT), 'name' => '建国記念の日', self::CHECK_TYPE_SINCE => 1967),
            array('date' => Date::toDate($year, 2, 23, self::FORMAT), 'name' => '天皇誕生日', self::CHECK_TYPE_SINCE => 2020),
            array('date' => Date::toDate($year, 3, static::getSpringEquinoxDay($year), self::FORMAT), 'name' => '春分の日'),
            array('date' => Date::toDate($year, 4, 29, self::FORMAT), 'name' => '昭和の日', self::CHECK_TYPE_SINCE => 2007),
            array('date' => Date::toDate($year, 5, 3, self::FORMAT), 'name' => '憲法記念日'),
            array('date' => Date::toDate($year, 5, 4, self::FORMAT), 'name' => 'みどりの日', self::CHECK_TYPE_SINCE => 2007),
            array('date' => Date::toDate($year, 5, 5, self::FORMAT), 'name' => 'こどもの日'),
            array('date' => Date::toDate($year, 7, Date::calcWeekDay($year, 7, 3, 1), self::FORMAT), 'name' => '海の日', self::CHECK_TYPE_SINCE => 2021),
            array('date' => Date::toDate($year, 8, 11, self::FORMAT), 'name' => '山の日', self::CHECK_TYPE_SINCE => 2021),
            array('date' => Date::toDate($year, 9, Date::calcWeekDay($year, 9, 3, 1), self::FORMAT), 'name' => '敬老の日', self::CHECK_TYPE_SINCE => 2003),
            array('date' => Date::toDate($year, 9, static::getAutumnalEquinoxDay($year), self::FORMAT), 'name' => '秋分の日'),
            array('date' => Date::toDate($year, 10, Date::calcWeekDay($year, 10, 2, 1), self::FORMAT), 'name' => 'スポーツの日', self::CHECK_TYPE_SINCE => 2021),
            array('date' => Date::toDate($year, 11, 3, self::FORMAT), 'name' => '文化の日'),
            array('date' => Date::toDate($year, 11, 23, self::FORMAT), 'name' => '勤労感謝の日'),

            // 旧
            array('date' => Date::toDate($year, 1, 15, self::FORMAT), 'name' => '成人の日', self::CHECK_TYPE_ABORT => 1999),
            array('date' => Date::toDate($year, 4, 29, self::FORMAT), 'name' => '天皇誕生日', self::CHECK_TYPE_ABORT => 1988),
            array('date' => Date::toDate($year, 4, 29, self::FORMAT), 'name' => 'みどりの日', self::CHECK_TYPE_SINCE => 1989, self::CHECK_TYPE_ABORT => 2006),
            array('date' => Date::toDate($year, 7, 20, self::FORMAT), 'name' => '海の日', self::CHECK_TYPE_SINCE => 1996, self::CHECK_TYPE_ABORT => 2002),
            array('date' => Date::toDate($year, 7, Date::calcWeekDay($year, 7, 3, 1), self::FORMAT), 'name' => '海の日', self::CHECK_TYPE_SINCE => 2003, self::CHECK_TYPE_ABORT => 2019),
            array('date' => Date::toDate($year, 8, 11, self::FORMAT), 'name' => '山の日', self::CHECK_TYPE_SINCE => 2016, self::CHECK_TYPE_ABORT => 2019),
            array('date' => Date::toDate($year, 9, 15, self::FORMAT), 'name' => '敬老の日', self::CHECK_TYPE_SINCE => 1966, self::CHECK_TYPE_ABORT => 2002),
            array('date' => Date::toDate($year, 10, 10, self::FORMAT), 'name' => '体育の日', self::CHECK_TYPE_SINCE => 1966, self::CHECK_TYPE_ABORT => 1999),
            array('date' => Date::toDate($year, 10, Date::calcWeekDay($year, 10, 2, 1), self::FORMAT), 'name' => '体育の日', self::CHECK_TYPE_SINCE => 2000, self::CHECK_TYPE_ABORT => 2019),
            array('date' => Date::toDate($year, 12, 23, self::FORMAT), 'name' => '天皇誕生日', self::CHECK_TYPE_SINCE => 1989, self::CHECK_TYPE_ABORT => Date::toDate(2019, 4, 30)),

            // 個別設定
            array('date' => Date::toDate($year, 4, 10, self::FORMAT), 'name' => '明仁親王の結婚の儀', self::CHECK_TYPE_SINCE => 1959, self::CHECK_TYPE_ABORT => 1959),
            array('date' => Date::toDate($year, 2, 24, self::FORMAT), 'name' => '昭和天皇の大喪の礼', self::CHECK_TYPE_SINCE => 1989, self::CHECK_TYPE_ABORT => 1989),
            array('date' => Date::toDate($year, 11, 12, self::FORMAT), 'name' => '即位礼正殿の儀', self::CHECK_TYPE_SINCE => 1990, self::CHECK_TYPE_ABORT => 1990),
            array('date' => Date::toDate($year, 6, 9, self::FORMAT), 'name' => '皇太子徳仁親王の結婚の儀', self::CHECK_TYPE_SINCE => 1993, self::CHECK_TYPE_ABORT => 1993),
            array('date' => Date::toDate($year, 5, 1, self::FORMAT), 'name' => '天皇即位の日', self::CHECK_TYPE_SINCE => 2019, self::CHECK_TYPE_ABORT => 2019),
            array('date' => Date::toDate($year, 10, 22, self::FORMAT), 'name' => '即位礼正殿の儀', self::CHECK_TYPE_SINCE => 2019, self::CHECK_TYPE_ABORT => 2019),

            // オリンピック関連
            array('date' => Date::toDate($year, 7, 23, self::FORMAT), 'name' => '海の日', self::CHECK_TYPE_SINCE => 2020, self::CHECK_TYPE_ABORT => 2020),
            array('date' => Date::toDate($year, 7, 24, self::FORMAT), 'name' => 'スポーツの日', self::CHECK_TYPE_SINCE => 2020, self::CHECK_TYPE_ABORT => 2020),
            array('date' => Date::toDate($year, 8, 10, self::FORMAT), 'name' => '山の日', self::CHECK_TYPE_SINCE => 2020, self::CHECK_TYPE_ABORT => 2020),
        );
    }

    public static function isSinceDate($config, $checkTarget): bool
    {
        return static::checkDate($config, $checkTarget, self::CHECK_TYPE_SINCE);
    }

    public static function isAbortDate($config, $checkTarget): bool
    {
        return static::checkDate($config, $checkTarget, self::CHECK_TYPE_ABORT);
    }

    protected static function checkDate($config, $checkTarget, $type): bool
    {
        if (!isset($config[$type])) {
            return true;
        }

        $date = $config[$type];
        if (is_int($date)) {
            $date = $type === self::CHECK_TYPE_SINCE ?
                Date::toDate($date, 1, 1) :
                Date::toDate($date, 12, 31)->setTime(23, 59, 59);
        }

        return !(($type === self::CHECK_TYPE_SINCE && $checkTarget < $date) ||
            ($type === self::CHECK_TYPE_ABORT && $date < $checkTarget));
    }

    /**
     * @param int $year
     * @return static[]
     * @throws Exception
     */
    public static function listOf(int $year): array
    {
        $list = static::getYearConfig($year);
        /** @var Holiday[] $newHolidays */
        $newHolidays = array();
        foreach ($list as $holiday) {
            $day = Date::object($holiday['date']);
            if (!static::checkDate($holiday, $day, self::CHECK_TYPE_SINCE) || !static::checkDate($holiday, $day, self::CHECK_TYPE_ABORT)) {
                continue;
            }
            $newHolidays[$day->format(self::FORMAT)] = static::init($holiday['name'], $day);
        }

        ksort($newHolidays);

        // 国民の休日
        if (1988 <= $year) {
            $prevHoliday = null;
            foreach ($newHolidays as $holiday) {
                if ($prevHoliday instanceof DateTime) {
                    $diff = Date::diffDate($holiday->getDate(), $prevHoliday);
                    if ($diff->days === 2) {
                        $prevHoliday->modify('+1 day');
                        $newHolidays[$prevHoliday->format(self::FORMAT)] = static::init('国民の休日', $prevHoliday);
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
                if (!isset($newHolidays[$nextDay->format(self::FORMAT)])) {
                    $newHolidays[$nextDay->format(self::FORMAT)] = static::init('振替休日', $nextDay);
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
    public static function getSpringEquinoxDay(int $year): int
    {
        return floor(20.8431 + 0.242194 * ($year - 1980)) - floor(($year - 1980) / 4);
    }

    /**
     * get autumnal equinox day
     * @param int $year
     * @return int
     */
    public static function getAutumnalEquinoxDay(int $year): int
    {
        return floor(23.2488 + 0.242194 * ($year - 1980)) - floor(($year - 1980) / 4);
    }
}