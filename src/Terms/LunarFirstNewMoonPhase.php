<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Stores first new moon phase of the lunar year
 */
readonly class LunarFirstNewMoonPhase extends NewMoonPhase
{
    public function __construct(public int $total, public float $jd, public int $year, public bool $leap) {}
}
