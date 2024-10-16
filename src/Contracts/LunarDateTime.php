<?php

namespace LucNham\LunarCalendar\Contracts;

/**
 * Representation of Lunar calendar date and time
 */
interface LunarDateTime extends ZoneAccessible, LunarDateTimeFormattable
{
    /**
     * Gets the Unix timestamp
     *
     * @return integer
     */
    public function getTimestamp(): int;
}
