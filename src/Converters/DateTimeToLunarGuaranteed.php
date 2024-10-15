<?php

namespace LucNham\LunarCalendar\Converters;

use DateTimeInterface;
use DateTimeZone;
use LucNham\LunarCalendar\Contracts\ZoneAccessible;
use LucNham\LunarCalendar\Terms\DateTimeInterval;

/**
 * Convert a object that implement DateTimeInterface to Lunar date time
 */
class DateTimeToLunarGuaranteed extends GregorianToLunarDateTime implements ZoneAccessible
{
    /**
     * Create new converter
     *
     * @param DateTimeInterface $datetime
     */
    public function __construct(private DateTimeInterface $datetime)
    {
        parent::__construct(
            gregorian: new DateTimeInterval(
                d: $datetime->format('j'),
                m: $datetime->format('n'),
                y: $datetime->format('Y'),
                h: $datetime->format('G'),
                i: (int)$datetime->format('i'),
                s: (int)$datetime->format('s')
            ),
            offset: $datetime->getOffset()
        );
    }

    /**
     * @inheritDoc
     */
    public function getTimezone(): DateTimeZone
    {
        return $this->datetime->getTimezone();
    }

    /**
     * @inheritDoc
     */
    public function getOffset(): int
    {
        return $this->offset();
    }
}
