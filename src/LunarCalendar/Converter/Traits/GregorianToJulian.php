<?php declare(strict_types=1);

namespace LunarCalendar\Converter\Traits;

use DateTimeInterface;

/**
 * This trait convert a Gregorian date time or PHP DateTimeInterface to a Julian
 * Days Count, includes decimal sub-day part
 * 
 * @author VanTran <caovan.info@gmail.com>
 */
trait GregorianToJulian
{
    public function gregorianToJd(int $day, int $month, int $year, int $hour = 0, int $minute = 0, int $second = 0): int|float
    {
        $jd  = gregoriantojd($month, $day, $year);
        $jd += ($hour + $minute / 60 + $second / 3600) / 24;

        return $jd;
    }
    
    public function dateTimeToJd(DateTimeInterface $dateTime): int|float
    {
        return $this->gregorianToJd(
            (int)$dateTime->format('d'),
            (int)$dateTime->format('m'),
            (int) $dateTime->format('Y'),
            (int)$dateTime->format('H'),
            (int)$dateTime->format('i'),
            (int)$dateTime->format('s')
        );
    }
}