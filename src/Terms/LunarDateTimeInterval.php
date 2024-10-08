<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Lunar date time interval, used to store output results or input parameters for converters.
 */
readonly class LunarDateTimeInterval extends DateTimeInterval
{
    /**
     * Undocumented function
     *
     * @param integer $d
     * @param integer $m
     * @param integer $y
     * @param integer $h
     * @param integer $i
     * @param integer $s
     * @param integer $l    Leap month number, default 0 mean unknow.
     * @param integer $t    Total days of month, default 0, mean unknow.
     */
    public function __construct(
        public int $d = 1,
        public int $m = 1,
        public int $y = 1970,
        public int $h = 0,
        public int $i = 0,
        public int $s = 0,
        public int $l = 0,
        public int $t = 0
    ) {}
}
