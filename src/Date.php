<?php

namespace Firaiz\Ufl;

use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

/**
 * Class Date
 * @package Firaiz\Ufl
 */
class Date
{
    final public const INTERVAL_TYPE_YEAR = 'Y';
    final public const INTERVAL_TYPE_MONTH = 'M';
    final public const INTERVAL_TYPE_DAY = 'D';
    final public const INTERVAL_TYPE_HOUR = 'H';
    final public const INTERVAL_TYPE_MINUETS = 'm';
    final public const INTERVAL_TYPE_SECOND = 'S';

    /**
     * @param string|null $timezone init timezone
     */
    public static function init(?string $timezone = 'Asia/Tokyo'): void
    {
        if (is_null($timezone)) {
            $timezone = date_default_timezone_get() ?: 'Asia/Tokyo';
        }
        date_default_timezone_set($timezone);
    }

    /**
     * @throws Exception
     */
    public static function week(DateTime|int|string|null $date): string
    {
        $weekText = ['日', '月', '火', '水', '木', '金', '土'];
        $date = static::object($date);
        return $weekText[$date->format('w')];
    }

    /**
     * @throws Exception
     */
    public static function object(DateTime|int|string|null $date = null): DateTime
    {
        if ($date instanceof DateTime) {
            return clone $date;
        }

        if (is_int($date)) {
            return (new DateTime())->setTimestamp($date);
        }
        if (is_null($date)) {
            return new DateTime();
        }
        return new DateTime($date);
    }

    /**
     * @return DateTime[]
     * @throws Exception
     */
    public static function getWeekDateList(DateTime|int|string|null $time, int $size = 1): array
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
     * @throws Exception
     */
    public static function createSimpleInterval(string $type, int $span = 1): DateInterval
    {
        $format = 'P';
        if ($type === 'H' || $type === 'm' || $type === 'S') {
            $format .= 'T';
        }
        return new DateInterval($format . $span . strtoupper($type));
    }

    /**
     * @return DateTime[]
     * @throws Exception
     */
    public static function getDateList(DateTime $startDate, DateTime $endDate): array
    {
        $dateIterator = new DatePeriod($startDate, static::createSimpleInterval(static::INTERVAL_TYPE_DAY), $endDate);

        $result = [];
        foreach ($dateIterator as $date) {
            $result[] = $date;
        }
        return $result;
    }

    /**
     * @throws Exception
     */
    public static function createYears(DateTime|int|string|null $startYear, int $dateMargin = 2): array
    {
        $startDate = static::object($startYear);

        $endDate = new DateTime();
        $endDate->add(static::createSimpleInterval(static::INTERVAL_TYPE_YEAR, $dateMargin));
        $dateIterator = new DatePeriod($startDate, static::createSimpleInterval(static::INTERVAL_TYPE_YEAR), $endDate);

        $result = [];
        foreach ($dateIterator as $date) {
            /** @var DateTime $date */
            $year = $date->format('Y');
            $result[$year] = $year;
        }
        return $result;
    }

    public static function createMonths(): array
    {
        return [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12];
    }

    /**
     * @throws Exception
     */
    public static function nowString(string $format = 'Y-m-d H:i:s'): string
    {
        return static::now()->format($format);
    }

    /**
     * @throws Exception
     */
    public static function now(): DateTime
    {
        return static::object();
    }

    /**
     * @throws Exception
     */
    public static function today(): DateTime
    {
        return static::toDayTime();
    }

    /**
     * @throws Exception
     */
    public static function toDayTime(DateTime|int|string|null $date = null): DateTime
    {
        $toDay = static::object($date);
        $toDay->setTime(0, 0);
        return $toDay;
    }

    /**
     * @throws Exception
     */
    public static function diffDate(DateTime $base, DateTime $target): DateInterval
    {
        $diffBase = static::toDayTime($base);
        $diffTarget = static::toDayTime($target);
        return $diffBase->diff($diffTarget);
    }

    /**
     * @param string $addType use INTERVAL_TYPE_* constants
     * @throws Exception
     */
    public static function add(string $addType, DateTime $date, int $dateCount): DateTime
    {
        $newDate = clone $date;
        $newDate->add(static::createSimpleInterval($addType, $dateCount));
        return $newDate;
    }

    /**
     * @throws Exception
     */
    public static function firstDayOfThisMonth(DateTime|int|string|null $date): DateTime
    {
        $date = static::toDayTime($date);
        $date->setDate($date->format('Y'), $date->format('m'), 1);
        return $date;
    }

    /**
     * @throws Exception
     */
    public static function lastDayOfThisMonth(DateTime|int|string|null $date): DateTime
    {
        $date = static::object($date);
        $date->modify('last day of this month');
        $date->setTime(23, 59, 59);
        return $date;
    }

    /**
     * @param $week 0-6 sunday - saturday
     */
    public static function weekToText($week): string
    {
        return match ($week) {
            0 => 'sunday',
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            default => '',
        };
    }

    /**
     * @param $weekNo 1-5
     */
    public static function monthWeekNoToText($weekNo): string
    {
        return match ($weekNo) {
            1 => 'first',
            2 => 'second',
            3 => 'third',
            4 => 'fourth',
            5 => 'fifth',
            default => '',
        };
    }

    /**
     * @param $weekNo 1-5
     * @param $week 0-6
     * @throws Exception
     */
    public static function calcWeekDay(int $year, int $month, $weekNo, $week): int
    {
        $date = static::object($year . '-' . $month);
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($date->format('w') == $week) {
            --$weekNo;
        }
        $weekText = static::weekToText($week);
        $weekNoText = static::monthWeekNoToText($weekNo);
        $date->modify($weekNoText . ' ' . $weekText);
        return $date->format('d') - 0;
    }

    /**
     * @param string|null $format
     * @throws Exception
     */
    public static function toDate(int $year, int $month, int $day, string $format = null): DateTime|string
    {
        $date = static::object(sprintf('%4d-%02d-%02d', $year, $month, $day));
        return is_null($format) ? $date : $date->format($format);
    }
}