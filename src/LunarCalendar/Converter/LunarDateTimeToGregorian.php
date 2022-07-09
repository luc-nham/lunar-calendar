<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use DateTime;
use LunarCalendar\Converter\Traits\LunarDateTimeToGregorian as TraitsLunarDateTimeToGregorian;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;

class LunarDateTimeToGregorian
{
    use TraitsLunarDateTimeToGregorian;

    protected $l_day;
    protected $l_month;
    protected $l_year;
    protected $l_leap;
    protected $timeZone;

    public function __construct(int $l_day, int $l_month, int $l_year, int $l_leap, float $timeZone)
    {
        $this->l_day    = $l_day;
        $this->l_month  = $l_month;
        $this->l_year   = $l_year;
        $this->l_leap   = $l_leap;
        $this->timeZone = $timeZone;
    }

    /**
     * Quick create instance from LunarDateTimeStorageInterface
     *
     * @param \LunarCalendar\Formatter\LunarDateTimeStorageInterface $lunarStorage
     * @return \LunarCalendar\Converter\LunarDateTimeToGregorian
     */
    public static function createFromLunarStorage(LunarDateTimeStorageInterface $lunarStorage): self 
    {
        return new self(
            $lunarStorage->getDay(),
            $lunarStorage->getMonth(),
            $lunarStorage->getYear(),
            $lunarStorage->getLeapMonthOffset(),
            $lunarStorage->getTimeZone()
        );
    }

    /**
     * Get ouput DateTime object
     *
     * @return DateTime
     */
    public function output(): DateTime
    {
        $gergorian = $this->lunarDateTimeToGregorian(
            $this->l_day,
            $this->l_month,
            $this->l_year,
            $this->l_leap,
            $this->timeZone
        );

        $datetime = new DateTime();
        $datetime->setDate($gergorian['Y'], $gergorian['m'], $gergorian['d']);
        $datetime->setTime(0, 0, 0, 0);

        return $datetime;
    }
}