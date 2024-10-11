<?php

namespace LucNham\LunarCalendar\Terms;

/**
 * Store time interval
 */
readonly class TimeInterval
{
    public function __construct(
        public int $h = 0,
        public int $i = 0,
        public int $s = 0,
    ) {}
}
