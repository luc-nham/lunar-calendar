<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Store new moon phase properties
 */
readonly class NewMoonPhase
{
    /**
     * Create new instance
     *
     * @param integer $total The total number of new moon cycles that have passed with 0 since 
     *                       1900-01-01, and each period thereafter is increased by 1 unit
     * @param float $jd      Julian day number at beginning point of current new moon
     */
    public function __construct(public int $total, public float $jd) {}
}
