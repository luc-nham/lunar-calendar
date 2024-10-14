<?php

namespace LucNham\LunarCalendar\Contracts;

use DateTimeZone;
use LucNham\LunarCalendar\Terms\LunarParsingResults;

/**
 * Lunar date time string parser
 */
interface LunarParser
{
    /**
     * Parse Lunar date time string into separated attributes
     *
     * @param string $lunar
     * @param DateTimeZone|null $timezone
     * @return LunarParsingResults
     */
    public function parse(string $lunar, ?DateTimeZone $timezone = null): LunarParsingResults;
}
