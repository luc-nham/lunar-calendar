<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * The class includes read-only properties that store time intervals, used to store output values ​​or 
 * provide as input to converters.
 */
readonly class DateTimeInterval
{
    public function __construct(
        public int $d = 1,
        public int $m = 1,
        public int $y = 1970,
        public int $h = 0,
        public int $i = 0,
        public int $s = 0,
    ) {}
}
