<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Lunar date time interval, used to store output results or input parameters for converters.
 */
readonly class LunarDateTimeInterval extends DateTimeInterval
{
    /**
     * Create new Lunar Date time interval. When used as input, not all attributes are needed.
     *
     * @param integer $d            Days, default 1.
     * @param integer $m            Months, default 1.
     * @param integer $y            Years, default 1970.
     * @param integer $h            Hours, default 0.
     * @param integer $i            Minutes, default 0.
     * @param integer $s            Seconds, default 0.
     * @param integer $l            The leap month number, default 0 mean unknown or does not exist.
     *                              This property can be calculate and assign by some converters. 
     *                              Default 0 mean unknown. 
     * @param boolean $leap         Check if the current month is leap, default false.
     */
    public function __construct(
        public int $d = 1,
        public int $m = 1,
        public int $y = 1970,
        public int $h = 0,
        public int $i = 0,
        public int $s = 0,
        public int $l = 0,
        public bool $leap = false
    ) {}
}
