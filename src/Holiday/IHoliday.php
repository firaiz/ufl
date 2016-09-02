<?php
/**
 * Created by PhpStorm.
 * User: k.yoshida
 * Date: 2016/09/02
 * Time: 9:44
 */

namespace UflAs\Holiday;


interface IHoliday
{
    /**
     * @param int $year
     * @return static[]
     */
    public static function listOf($year);
}