<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Store a Solar longitude milestone
 */
readonly class SolarLongitudeMileStone
{
    /**
     * Create new milestone. Attributes are bound to each other, so they have no default value.
     *
     * @param float $jd         Julian day number
     * @param float $angle      Angle corresponding to Julian day number
     * @param string $datetime  A date time string corresponding to Julian day number
     */
    public function __construct(
        public float $jd,
        public float $angle,
        public string $datetime
    ) {}
}
