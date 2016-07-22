<?php
namespace AnySys;

use DateInterval;
use DatePeriod;
use DateTime;

class Date
{
    const INTERVAL_TYPE_YEAR = 'Y';
    const INTERVAL_TYPE_MONTH = 'M';
    const INTERVAL_TYPE_DAY = 'D';
    const INTERVAL_TYPE_HOUR = 'H';
    const INTERVAL_TYPE_MINUETS = 'm';
    const INTERVAL_TYPE_SECOND = 'S';

    /**
     * @param string $type
     * @param int $span
     * @return DateInterval
     */
    public static function createSimpleInterval($type, $span = 1) {
        $format = 'P';
        if (in_array($type, array('H', 'm', 'S'))) {
            $format .= 'T';
        }
        return new DateInterval($format.$span.strtoupper($type));
    }

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
     * @param DateTime $startDate
     * @param DateTime $endDate
     * @return DateTime[]
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
     */
    public static function nowString($format = 'Y-m-d H:i:s')
    {
        return static::now()->format($format);
    }

    /**
     * @return DateTime
     */
    public static function now()
    {
        return static::object();
    }

    /**
     * @return DateTime
     */
    public static function today()
    {
        return static::toDayTime();
    }

    /**
     * @param string|int|DateTime|null $date
     * @return DateTime
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
     */
    public static function firstDayOfThisMonth($date) {
        $date = static::toDayTime($date);
        $date->setDate($date->format('Y'), $date->format('m'), 1);
        return $date;
    }

    /**
     * @param string|int|DateTime $date
     * @return DateTime
     */
    public static function lastDayOfThisMonth($date) {
        $date = static::object($date);
        $date->modify('last day of this month');
        $date->setTime(23, 59, 59);
        return $date;
    }
}