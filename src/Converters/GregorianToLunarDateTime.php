<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;

/**
 * Converts a Gregorian date time interval to Lunar date time interval
 */
class GregorianToLunarDateTime extends JdToLunarDateTime
{
    /**
     * Create new converter
     *
     * @param DateTimeInterval $gregorian Gregorian input, default 1970-01-01T00:00
     * @param integer $offset             Timezone offset in seconds, default 0 mean UTC
     */
    public function __construct(private DateTimeInterval $gregorian = new DateTimeInterval(), int $offset = 0)
    {
        parent::__construct(
            jd: (new GregorianToJd($this->gregorian, $offset))->getOutput(),
            offset: $offset
        );
    }
}
