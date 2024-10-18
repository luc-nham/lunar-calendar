<?php

namespace LucNham\LunarCalendar\Converters;

use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;

/**
 * Converts a guaranteed Lunar date time to Gregorian with format 'Y-m-d H:i:s P'
 */
class LunarDateTimeToDateTimeString extends Converter
{
    public function __construct(private LunarDateTimeInterval $lunar, int $offset = 0)
    {
        $this->setOffset($offset);
    }

    /**
     * Return date time string with a popular and convenient form for use: 'Y-m-d H:i:s P'
     *
     * @return string
     */
    public function getOutput(): string
    {
        return (new LunarDateTimeToGregorian(
            lunar: $this->lunar,
            offset: $this->offset(),
        ))
            ->then(DateTimeIntervalToDateTimeString::class)
            ->getOutput();
    }
}
