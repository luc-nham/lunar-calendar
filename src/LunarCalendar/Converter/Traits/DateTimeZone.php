<?php declare(strict_types=1);

namespace LunarCalendar\Converter\Traits;

use DateTime;
use DateTimeZone as GlobalDateTimeZone;

trait DateTimeZone
{
    /**
     * Convert timezone string to float
     *
     * @param string $timezone 
     * @link         https://www.php.net/manual/en/datetimezone.construct.php
     * @return float 
     */
    public function stringTimeZoneToFloat(string $timezone): int|float
    {
        $timezone = new GlobalDateTimeZone($timezone);
        $dateTime = new DateTime();

        return $timezone->getOffset($dateTime) / 3600;
    }
}