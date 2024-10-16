<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * This class is used to store lunar values ​​whose accuracy is guaranteed, suitable for deployment 
 * with converters whose output is lunar time. All of its properties have no default value, and must 
 * be explicitly specified only once.
 */
readonly class LunarDateTimeGuaranteed extends LunarDateTimeInterval
{
    /**
     * Create new Lunar Date time guaranteed.
     *
     * @param integer $d            Days
     * @param integer $m            Months
     * @param integer $y            Years
     * @param integer $h            Hours
     * @param integer $i            Minutes
     * @param integer $s            Seconds
     * @param integer $l            The leap month number
     * @param boolean $leap         Check if the current month is leap, default false.
     * @param float $j              Julian day number corresponding to Lunar date time
     */
    public function __construct(
        public int $d,
        public int $m,
        public int $y,
        public int $h,
        public int $i,
        public int $s,
        public int $l,
        public bool $leap,
        public float $j
    ) {}
}
