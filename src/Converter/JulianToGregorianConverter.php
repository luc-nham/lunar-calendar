<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\DateTimeFormatter;

class JulianToGregorianConverter
{
    protected $jd;

    public function __construct(float $jd)
    {
        $this->jd = $jd;
    }

    public function getDateTime(): DateTimeFormatter
    {
        $mainjdday  = floor($this->jd);
        $subjdday   = $this->jd - $mainjdday;

        if ($mainjdday > 2299160) { // After 5/10/1582, Gregorian calendar
            $a      = $mainjdday + 32044;
            $b      = floor((4 * $a + 3) / 146097);
            $c      = $a - floor(($b * 146097) / 4);
        } 
        else {
            $b      = 0;
            $c      = $mainjdday + 32082;
        }
        
        $d          = floor((4 * $c + 3) / 1461);
        $e          = $c - floor((1461 * $d) / 4);
        $m          = floor((5 * $e + 2) / 153);

        $dateTimeOuput = new DateTimeFormatter();

        $dateTimeOuput->setDate(
            d: (int)($e - floor((153 * $m + 2) / 5) + 1),
            m: (int)($m + 3 - 12 * floor($m / 10)),
            Y: (int)($b * 100 + $d - 4800 + floor($m / 10))
        );

        // Get H, i,s
        $seconds = floor($subjdday * 3600 * 24) + 1;

        $dateTimeOuput->setTime(
            H: ($seconds / 3600) % 24,
            i: ($seconds / 60) % 60,
            s: $seconds % 60
        );

        return $dateTimeOuput;
    }
}