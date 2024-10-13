<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;

/**
 * Converts a Lunar date time interval to Gregorian date time
 */
class LunarDateTimeToGregorian extends Converter
{
    /**
     * Create new converter
     *
     * @param LunarDateTimeInterval $lunar  Lunar date time interval
     * @param integer $offset               Timezone offset in seconds, default 0 mean UTC
     */
    public function __construct(
        private LunarDateTimeInterval $lunar = new LunarDateTimeInterval(),
        int $offset = 0
    ) {
        $this->setOffset($offset);
    }

    /**
     * Return the Gregorian date time
     *
     * @return DateTimeInterval
     */
    public function getOutput(): DateTimeInterval
    {
        return (new LunarDateTimeToJd(
            lunar: $this->lunar,
            offset: $this->offset()
        ))
            ->then(JdToGregorian::class)
            ->getOutput();
    }
}
