<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

interface LunarDateTimeStorageInterface
{
    const DAY               = 'd';
    const MONTH             = 'm';
    const YEAR              = 'Y';
    const HOURS             = 'H';
    const MINUTES           = 'i';
    const SECONDS           = 's';
    const LEAPMONTH_OFFSET  = 'l';
    const LEAPYEAR_OFFSET   = 'L';
    const JULIAN_DAY_COUNT  = 'j';
    const TIME_ZONE         = 'o';

    /**
     * Quick create instance
     *
     * @return self
     */
    public static function create(): self;

    /**
     * Store date time value
     *
     * @param string $key
     * @param mixed $value
     * @return self
     */
    public function set(string $key, mixed $value): self;

    /**
     * Store day number
     *
     * @param integer|string $day
     * @return self
     */
    public function setDay(int|string $day): self;

    /**
     * Store month number
     *
     * @param integer|string $month
     * @return self
     */
    public function setMonth(int|string $month): self;

    /**
     * Store year number
     *
     * @param integer|string $year
     * @return self
     */
    public function setYear(int|string $year): self;

    /**
     * Store Leap month offset
     *
     * @param integer $leapMonthOffset
     * @return self
     */
    public function setLeapMonthOffset(int $leapMonthOffset): self;

    /**
     * Store leap year offset
     *
     * @param integer $leapMonthOffset
     * @return self
     */
    public function setLeapYearOffset(int $leapMonthOffset): self;

    /**
     * Store timezone
     *
     * @param integer|float $timezone
     * @return self
     */
    public function setTimeZone(int|float $timezone): self;

    /**
     * Store Julian day count
     *
     * @param integer|float $jd
     * @return self
     */
    public function setJulianDayCount(int|float $jd):self;

    /**
     * Store hours in 24h format
     *
     * @param integer|string $hour24Format
     * @return self
     */
    public function setHours(int|string $hour24Format): self;

    /**
     * Store minutes
     *
     * @param integer|string $minutes
     * @return self
     */
    public function setMinutes(int|string $minutes): self;

    /**
     * Store seconds
     *
     * @param integer|string $seconds
     * @return self
     */
    public function setSeconds(int|string $seconds): self;

    /**
     * Get date time value
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Check if a value stored
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool;

    /**
     * Return day number of month - should be 1 through 30
     *
     * @return integer|string
     */
    public function getDay(): int|string;

    /**
     * Retrun month number of year - should be 1 through 12
     *
     * @return integer|string
     */
    public function getMonth(): int|string;

    /**
     * Return year number with 4 digits format
     *
     * @return integer|string
     */
    public function getYear(): int|string;

    /**
     * Get Hour in 24h format
     *
     * @return integer|string
     */
    public function getHours(): int|string;

    /**
     * Get minutes
     *
     * @return integer|string
     */
    public function getMinutes(): int|string;

    /**
     * Get seconds
     *
     * @return integer|string
     */
    public function getSeconds(): int|string;

    /**
     * Get leap month offset: 0 is not a leap month, 1 is leap month
     *
     * @return integer
     */
    public function getLeapMonthOffset(): int;

    /**
     * Get leap year offset: 0 is not a leap month, 1 is leap month
     *
     * @return integer
     */
    public function getLeapYearOffset(): int;

    /**
     * Get timezone
     *
     * @return integer|float
     */
    public function getTimeZone(): int|float;

    /**
     * Get Julian day count
     *
     * @return integer|float
     */
    public function getJulianDayCount(): int|float;
}