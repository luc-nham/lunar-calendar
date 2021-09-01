<?php declare(strict_types=1);

namespace LunarCalendar;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use LunarCalendar\Formatter\DateTimeFormatter;

class BaseDateTime extends DateTimeFormatter
{
    public static function createFromDateTime(?DateTimeInterface $dateTime = null, ?DateTimeZone $dateTimeZone = null): DateTimeFormatter
    {
        if(null === $dateTime) {
            $dateTime = new DateTime('now', $dateTimeZone);
        }

        return new BaseDateTime(
            (int)$dateTime->format('d'),
            (int)$dateTime->format('m'),
            (int)$dateTime->format('Y'),
            (int)$dateTime->format('H'),
            (int)$dateTime->format('i'),
            (int)$dateTime->format('s'),
            (float)$dateTime->getOffset() / 3600
        );
    }

    public static function createFromString(string $datetime = 'now', ?DateTimeZone $dateTimeZone = null): DateTimeFormatter
    {
        $datetimeObj = new DateTime($datetime, $dateTimeZone);
        return self::createFromDateTime($datetimeObj);
    }
}