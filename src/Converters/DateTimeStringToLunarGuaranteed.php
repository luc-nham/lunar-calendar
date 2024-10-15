<?php

namespace LucNham\LunarCalendar\Converters;

use DateTime;
use DateTimeZone;

/**
 * Converts a date time (gregorian) string to Lunar date time
 */
class DateTimeStringToLunarGuaranteed extends DateTimeToLunarGuaranteed
{
    public function __construct(private string $datetime = 'now', private ?DateTimeZone $timezone = null)
    {
        parent::__construct(new DateTime($datetime, $timezone));
    }
}
