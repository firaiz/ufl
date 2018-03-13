<?php
namespace UflAs\Holiday;


interface IHoliday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf($year);
}