<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Store a New moon phase of Lunar leap month
 */
readonly class LunarLeapMonthNewMoonPhase extends NewMoonPhase
{
    /**
     * Create new Lunar leap month New moon phase
     *
     * @param integer $total The total number of new moon cycles that have passed with 0 since 
     *                       1900-01-01, and each period thereafter is increased by 1 unit
     * @param float $jd      Julian day number at beginning point of current new moon
     * @param integer $month The leap month number, can be betwen 2 to 11
     */
    public function __construct(public int $total, public float $jd, public int $month) {}
}
