<?php

namespace LucNham\LunarCalendar\Contracts;

use DateTimeZone;

/**
 * Can receive time zone and offset
 */
interface ZoneAccessible
{
    /**
     * Returns DateTimeZone object
     *
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone;

    /**
     * Returns UTC offset in seconds
     *
     * @return integer
     */
    public function getOffset(): int;
}
