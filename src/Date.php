<?php
namespace AnySys;

use DateInterval;
use DatePeriod;
use DateTime;

class Date
{
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
            return $date;
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
            $startDate->sub(new DateInterval('P1D'));
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
        $dateIterator = new DatePeriod($startDate, new DateInterval('P1D'), $endDate);

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
        $endDate->add(new DateInterval('P' . $dateMargin . 'Y'));

        $dateIterator = new DatePeriod($startDate, new DateInterval('P1Y'), $endDate);
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
        $today = static::object();
        $today->setTime(0, 0, 0);
        return $today;
    }

    /**
     * @param DateTime $base
     * @param DateTime $target
     * @return bool|DateInterval
     */
    public static function diffDate($base, $target)
    {
        $diffBase = clone $base;
        $diffBase->setTime(0, 0);
        $diffTarget = clone $target;
        $diffTarget->setTime(0, 0);
        return $diffBase->diff($diffTarget);
    }

    /**
     * @param DateTime $date
     * @param int $dateCount
     * @return DateTime
     */
    public static function addDay($date, $dateCount)
    {
        $newDate = clone $date;
        $newDate->add(new DateInterval('P' . $dateCount . 'D'));
        return $newDate;
    }
}