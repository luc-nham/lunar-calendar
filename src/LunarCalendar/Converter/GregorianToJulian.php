<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

trait GregorianToJulian
{
    public function gregorianToJd(int $day, int $month, int $year, int $hour = 0, int $minute = 0, int $second = 0): float
    {
        $jd  = gregoriantojd($month, $day, $year);
        $jd += ($hour + $minute / 60 + $second / 3600) / 24;

        return $jd;
    }
}