<?php

namespace LucNham\LunarCalendar\Contracts;

/**
 * Representation of Lunar calendar date and time
 * 
 * @property integer $day           Lunar day number
 * @property integer $month         Lunar month number
 * @property integer $year          Lunar year number 
 * @property integer $hour          Lunar hours from 0 to 23
 * @property integer $minute        Lunar minutes from 0 to 59
 * @property integer $second        Lunar second from 0 to 59
 * @property integer $leap          The leap month number, 0 if no leap month
 * @property bool $isLeapMonth      Check if current lunar month be leap
 * @property float $jdn             Julian day number corresponding to Lunar date time
 * @property float $timestamp       Unix timestamp in seconds
 */
interface LunarDateTime extends ZoneAccessible, LunarDateTimeFormattable, LunarGuaranteedAccessible
{
    /**
     * Gets the Unix timestamp
     *
     * @return integer
     */
    public function getTimestamp(): int;
}
