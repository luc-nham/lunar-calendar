<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Converter\Traits\BaseCalendarConverters;
use LunarCalendar\Converter\Traits\GregorianToJulian;
use LunarCalendar\Formatter\LunarDateTimeStorage;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;

class GregorianToLunarDateTime
{
    use BaseCalendarConverters;
    use GregorianToJulian;

    /**
     * Input gregorian
     */
    private $d;
    private $m;
    private $Y;
    private $H;
    private $i;
    private $s;
    private $timeZone;

    /**
     * Output Lunar date time
     *
     * @var LunarDateTimeStorageInterface
     */
    protected $datetime;

    /**
     * Constructor
     *
     * @param integer $d
     * @param integer $m
     * @param integer $Y
     * @param integer $H
     * @param integer $i
     * @param integer $s
     * @param float $timeZone
     */
    public function __construct(int|string $d, int|string  $m, int|string  $Y, int|string  $H, int|string  $i, int|string  $s, float $timeZone)
    {
        $this->d = $d;
        $this->m = $m;
        $this->Y = $Y;
        $this->H = $H;
        $this->i = $i;
        $this->s = $s;
        $this->timeZone = $timeZone;

        // Set output
        $this->datetime = $this->_convert();
    }

    private function getJd(): float
    {
        $jd = $this->gregorianToJd($this->d, $this->m, $this->Y, $this->H, $this->i, $this->s);

        if($this->H == 23) {
            $jd += 1;
        }

        return $jd;
    }

    /**
     * Convert a input to Lunar date time
     *
     * @return LunarDateTimeStorage
     */
    private function _convert(): LunarDateTimeStorage
    {
        $inputJd    = $this->getJd();
        $dayNumber  = floor($inputJd);
        $k          = floor(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = $this->getJdNewMoon($k + 1, $this->timeZone);

        if ($monthStart > $dayNumber) {
            $monthStart = $this->getJdNewMoon($k, $this->timeZone);
        }

        $a11 = $this->getJdLunarNovemberNewMoon($this->Y, $this->timeZone);
        $b11 = $a11;

        if ($a11 >= $monthStart) {
            $lunarYear = $this->Y;
            $a11 = $this->getJdLunarNovemberNewMoon($this->Y - 1, $this->timeZone);
        } else {
            $lunarYear = $this->Y + 1;
            $b11 = $this->getJdLunarNovemberNewMoon($this->Y + 1, $this->timeZone);
        }

        $lunarDay   = $dayNumber - $monthStart + 1;
        $diff       = floor(($monthStart - $a11)/29);
        $lunarLeap  = false;
        $lunarMonth = $diff + 11;

        if ($b11 - $a11 > 365) {
            $leapMonthDiff = $this->getLeapMonthOffset($a11, $this->timeZone);

            if ($diff >= $leapMonthDiff) {
                $lunarMonth = $diff + 10;

                if ($diff == $leapMonthDiff) {
                    $lunarLeap = true;
                }
            }
        }

        if ($lunarMonth > 12) {
            $lunarMonth = $lunarMonth - 12;
        }
        if ($lunarMonth >= 11 && $diff < 4) {
            $lunarYear -= 1;
        }

        return LunarDateTimeStorage::create()
            ->setDay((int)$lunarDay)
            ->setMonth((int)$lunarMonth)
            ->setYear((int)$lunarYear)
            ->setHours($this->H)
            ->setMinutes($this->i)
            ->setSeconds($this->s)
            ->setTimeZone($this->timeZone)
            ->setLeapMonthOffset((int)$lunarLeap)
            ->setJulianDayCount($inputJd);
    }

    /**
     * Convert Solar to Lunar and store result
     *
     * @return \LunarCalendar\Formatter\LunarDateTimeStorageInterface
     */
    public function output(): LunarDateTimeStorageInterface
    {
        if(!$this->datetime) {
            $this->datetime = $this->_convert();
        }

        return $this->datetime;
    }
}