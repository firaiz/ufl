<?php
namespace UflAs;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

class Date
{
    const INTERVAL_TYPE_YEAR = 'Y';
    const INTERVAL_TYPE_MONTH = 'M';
    const INTERVAL_TYPE_DAY = 'D';
    const INTERVAL_TYPE_HOUR = 'H';
    const INTERVAL_TYPE_MINUETS = 'm';
    const INTERVAL_TYPE_SECOND = 'S';

    /**
     * @param string $timezone init timezone
     */
    public static function init($timezone = 'Asia/Tokyo')
    {
        date_default_timezone_set($timezone);
    }

    /**
     * @param string|int|DateTime $date
     * @return string
     * @throws Exception
     */
    public static function week($date)
    {
        $weekText = array('日', '月', '火', '水', '木', '金', '土');
        $date = static::object($date);
        return $weekText[$date->format('w')];
    }

    /**
     * @param string|int|DateTime
     * @return DateTime
     * @throws Exception
     */
    public static function object($date = null)
    {
        if ($date instanceof DateTime) {
            return clone $date;
        } elseif (is_int($date)) {
            $obj = new DateTime();
            return $obj->setTimestamp($date);
        }
        return new DateTime($date);
    }

    /**
     * @param string|int|DateTime $time
     * @param int $size
     * @return DateTime[]
     * @throws Exception
     */
    public static function getWeekDateList($time, $size = 1)
    {
        $startDate = static::object($time);
        if ($startDate->format('w') === '0') {
            $startDate->sub(static::createSimpleInterval(static::INTERVAL_TYPE_DAY));
        }
        $startDate->modify('this week monday');
        $endDate = clone $startDate;
        $endDate->modify('+' . $size . ' week');

        return static::getDateList($startDate, $endDate);
    }

    /**
     * @param string $type
     * @param int $span
     * @return DateInterval
     * @throws Exception
     */
    public static function createSimpleInterval($type, $span = 1)
    {
        $format = 'P';
        if ($type === 'H' || $type === 'm' || $type === 'S') {
            $format .= 'T';
        }
        return new DateInterval($format . $span . strtoupper($type));
    }

    /**
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return DateTime[]
     * @throws Exception
     */
    public static function getDateList($startDate, $endDate)
    {
        $dateIterator = new DatePeriod($startDate, static::createSimpleInterval(static::INTERVAL_TYPE_DAY), $endDate);

        $result = array();
        foreach ($dateIterator as $date) {
            $result[] = $date;
        }
        return $result;
    }

    /**
     * @param string|int|DateTime $startYear
     * @param int $dateMargin
     * @return array
     * @throws Exception
     */
    public static function createYears($startYear, $dateMargin = 2)
    {
        $startDate = static::object($startYear);

        $endDate = new DateTime();
        $endDate->add(static::createSimpleInterval(static::INTERVAL_TYPE_YEAR, $dateMargin));
        $dateIterator = new DatePeriod($startDate, static::createSimpleInterval(static::INTERVAL_TYPE_YEAR), $endDate);

        $result = array();
        foreach ($dateIterator as $date) {
            /** @var DateTime $date */
            $result[$date->format('Y')] = $date->format('Y');
        }
        return $result;
    }

    /**
     * @return array
     */
    public static function createMonths()
    {
        return array(
            1 => 1,
            2 => 2,
            3 => 3,
            4 => 4,
            5 => 5,
            6 => 6,
            7 => 7,
            8 => 8,
            9 => 9,
            10 => 10,
            11 => 11,
            12 => 12
        );
    }

    /**
     * @param string $format
     * @return string
     * @throws Exception
     */
    public static function nowString($format = 'Y-m-d H:i:s')
    {
        return static::now()->format($format);
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public static function now()
    {
        return static::object();
    }

    /**
     * @return DateTime
     * @throws Exception
     */
    public static function today()
    {
        return static::toDayTime();
    }

    /**
     * @param string|int|DateTime|null $date
     * @return DateTime
     * @throws Exception
     */
    public static function toDayTime($date = null)
    {
        $toDay = static::object($date);
        $toDay->setTime(0, 0, 0);
        return $toDay;
    }

    /**
     * @param DateTime $base
     * @param DateTime $target
     * @return bool|DateInterval
     * @throws Exception
     */
    public static function diffDate($base, $target)
    {
        $diffBase = static::toDayTime($base);
        $diffTarget = static::toDayTime($target);
        return $diffBase->diff($diffTarget);
    }

    /**
     * @param string $addType use INTERVAL_TYPE_* constants
     * @param DateTime $date
     * @param int $dateCount
     * @return DateTime
     * @throws Exception
     */
    public static function add($addType, $date, $dateCount)
    {
        $newDate = clone $date;
        $newDate->add(static::createSimpleInterval($addType, $dateCount));
        return $newDate;
    }

    /**
     * @param string|int|DateTime $date
     * @return DateTime
     * @throws Exception
     */
    public static function firstDayOfThisMonth($date)
    {
        $date = static::toDayTime($date);
        $date->setDate($date->format('Y'), $date->format('m'), 1);
        return $date;
    }

    /**
     * @param string|int|DateTime $date
     * @return DateTime
     * @throws Exception
     */
    public static function lastDayOfThisMonth($date)
    {
        $date = static::object($date);
        $date->modify('last day of this month');
        $date->setTime(23, 59, 59);
        return $date;
    }

    /**
     * @param $week 0-6 sunday - saturday
     * @return string
     */
    public static function weekToText($week) {
        switch ($week) {
            case 0:
                return 'sunday';
            case 1:
                return 'monday';
            case 2:
                return 'tuesday';
            case 3:
                return 'wednesday';
            case 4:
                return 'thursday';
            case 5:
                return 'friday';
            case 6:
                return 'saturday';
        }
        return '';
    }

    /**
     * @param $weekNo 1-5
     * @return string
     */
    public static function monthWeekNoToText($weekNo) {
        switch ($weekNo) {
            case 1:
                return 'first';
            case 2:
                return 'second';
            case 3:
                return 'third';
            case 4:
                return 'fourth';
            case 5:
                return 'fifth';
        }
        return '';
    }

    /**
     * @param int $year
     * @param int $month
     * @param $weekNo 1-5
     * @param $week 0-6
     * @return int
     * @throws Exception
     */
    public static function calcWeekDay($year, $month, $weekNo, $week)
    {
        $date = static::object($year.'-'.$month);
        if ($date->format('w') == $week) {
            $weekNo -= 1;
        }
        $weekText = static::weekToText($week);
        $weekNoText = static::monthWeekNoToText($weekNo);
        $date->modify($weekNoText.' '.$weekText);
        return $date->format('d') - 0;
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @param string [$format]
     * @return DateTime|string
     * @throws Exception
     */
    public static function toDate($year, $month, $day, $format = null) {
        $date = static::object(sprintf('%4d-%02d-%02d', $year, $month, $day));
        return is_null($format) ? $date : $date->format($format);
    }
}