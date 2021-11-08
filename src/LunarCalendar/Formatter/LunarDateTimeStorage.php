<?php declare(strict_types=1);

namespace LunarCalendar\Formatter;

class LunarDateTimeStorage implements LunarDateTimeStorageInterface
{
    protected $datetime = [
        'H'     => 0,
        'i'     => 0,
        's'     => 0,
        // 'd'     => 1,
        // 'm'     => 1,
        // 'Y'     => 1990,
        // 'l'     => 0,
        // 'L'     => 0,
        // 'j'     => 0,
        // 'o'     => 0
    ];

    /**
     * {@inheritDoc}
     */
    public static function create(): self
    {
        return new self;
    }

    /**
     * {@inheritDoc}
     */
    public function has(string $key): bool
    {
        return (array_key_exists($key, $this->datetime))
                    ? true
                    : false;
    }

    /**
     * {@inheritDoc}
     */
    public function get(string $key): mixed
    {
        return ($this->has($key))
                    ? $this->datetime[$key]
                    : throw new \Exception("Try to get value does not exist with key '$key'");
                    
    }

    /**
     * {@inheritDoc}
     */
    public function set(string $key, mixed $value): self
    {
        $this->datetime[$key] = $value;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setDay(int|string $day): LunarDateTimeStorageInterface
    {
        if($day < 1 || $day > 30) {
            throw new \Exception("Error. Lunar day must be a number from 1 through 30."); 
        }

        return $this->set(self::DAY, $day);
    }

    /**
     * {@inheritDoc}
     */
    public function setMonth(int|string $month): LunarDateTimeStorageInterface
    {
        if($month < 1 || $month > 12) {
            throw new \Exception("Error. Lunar month must be a number from 1 through 12.");
        }

        return $this->set(self::MONTH, $month);
    }

    /**
     * {@inheritDoc}
     */
    public function setYear(int|string $year): LunarDateTimeStorageInterface
    {
        if($year < 1) {
            throw new \Exception("Error. Minium Lunar year number must be 1.");
        }

        return $this->set(self::YEAR, $year);
    }

    /**
     * {@inheritDoc}
     */
    public function setHours(int|string $hour24Format): LunarDateTimeStorageInterface
    {
        if($hour24Format < 0 || $hour24Format > 23) {
            throw new \Exception("Error. Hours format must be a number from 0 to 23.");
        }

        return $this->set(self::HOURS, $hour24Format);
    }

    /**
     * {@inheritDoc}
     */
    public function setMinutes(int|string $minutes): LunarDateTimeStorageInterface
    {
        if($minutes < 0 || $minutes > 59) {
            throw new \Exception("Error. Minutes format must be a number from 0 to 59.");
        }

        return $this->set(self::MINUTES, $minutes);
    }

    /**
     * {@inheritDoc}
     */
    public function setSeconds(int|string $seconds): LunarDateTimeStorageInterface
    {
        if($seconds < 0 || $seconds > 59) {
            throw new \Exception("Error. Seconds format must be a number from 0 to 59.");
        }

        return $this->set(self::SECONDS, $seconds);
    }

    /**
     * {@inheritDoc}
     */
    public function setTimeZone(int|float $timezone): LunarDateTimeStorageInterface
    {
        return $this->set(self::TIME_ZONE, $timezone);
    }

    /**
     * {@inheritDoc}
     */
    public function setJulianDayCount(int|float $jd): LunarDateTimeStorageInterface
    {
        return $this->set(self::JULIAN_DAY_COUNT, $jd);
    }

    /**
     * {@inheritDoc}
     */
    public function setLeapMonthOffset(int $leapMonthOffset): LunarDateTimeStorageInterface
    {
        if($leapMonthOffset < 0 || $leapMonthOffset > 1) {
            throw new \Exception("Error. Leap month offset should be 0 or 1.");
        }

        return $this->set(self::LEAPMONTH_OFFSET, $leapMonthOffset);
    }

    /**
     * {@inheritDoc}
     */
    public function setLeapYearOffset(int $leapMonthOffset): LunarDateTimeStorageInterface
    {
        if($leapMonthOffset < 0 || $leapMonthOffset > 1) {
            throw new \Exception("Error. Leap year offset should be 0 or 1.");
        }

        return $this->set(self::LEAPYEAR_OFFSET, $leapMonthOffset);
    }

    /**
     * {@inheritDoc}
     */
    public function getDay(): int|string
    {
        if(!$this->has(self::DAY)) {
            throw new \Exception("Error. Lunar day number is not set before.");
        }

        return $this->datetime[self::DAY];
    }

    /**
     * {@inheritDoc}
     */
    public function getMonth(): int|string
    {
        if(!$this->has(self::MONTH)) {
            throw new \Exception("Error. Lunar month number is not set before.");
        }

        return $this->datetime[self::MONTH];
    }

    /**
     * {@inheritDoc}
     */
    public function getYear(): int|string
    {
        if(!$this->has(self::YEAR)) {
            throw new \Exception("Error. Lunar month number is not set before."); 
        }

        return $this->datetime[self::YEAR];
    }

    /**
     * {@inheritDoc}
     */
    public function getHours(): int|string
    {
        if(!$this->has(self::HOURS)) {
            throw new \Exception("Error. Lunar hours is not set before."); 
        }

        return $this->datetime[self::HOURS];
    }

    /**
     * {@inheritDoc}
     */
    public function getMinutes(): int|string
    {
        if(!$this->has(self::MINUTES)) {
            throw new \Exception("Error. Lunar minutes is not set before."); 
        }
        
        return $this->datetime[self::MINUTES];
    }

    /**
     * {@inheritDoc}
     */
    public function getSeconds(): int|string
    {
        if(!$this->has(self::SECONDS)) {
            throw new \Exception("Error. Lunar seconds is not set before."); 
        }

        return $this->datetime[self::SECONDS];
    }

    /**
     * {@inheritDoc}
     */
    public function getLeapMonthOffset(): int
    {
        if(!$this->has(self::LEAPMONTH_OFFSET)) {
            throw new \Exception("Error. Lunar leap month offset is not set before."); 
        }

        return $this->datetime[self::LEAPMONTH_OFFSET];
    }

    /**
     * {@inheritDoc}
     */
    public function getLeapYearOffset(): int
    {
        if(!$this->has(self::LEAPYEAR_OFFSET)) {
            throw new \Exception("Error. Lunar leap year offset is not set before."); 
        }

        return $this->datetime[self::LEAPYEAR_OFFSET];
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTimeZone(): int|float
    {
        if(!$this->has(self::TIME_ZONE)) {
            throw new \Exception("Error. Timezone is not set before."); 
        }

        return $this->datetime[self::TIME_ZONE];
    }

    /**
     * {@inheritDoc}
     */
    public function getJulianDayCount(): int|float
    {
        if(!$this->has(self::JULIAN_DAY_COUNT)) {
            throw new \Exception("Error. Julian day count is not set before."); 
        }

        return $this->datetime[self::JULIAN_DAY_COUNT];
    }
}