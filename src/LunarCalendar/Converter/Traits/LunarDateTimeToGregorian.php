<?php declare(strict_types=1);

namespace LunarCalendar\Converter\Traits;

trait LunarDateTimeToGregorian
{   
    use BaseCalendarConverters;
    use GregorianToJulian;
    use JulianToGregorian;
    use JulianToSunlongitude;
    
    /**
     * Validate input
     *
     * @param integer $l_day    Lunar day number
     * @param integer $l_month  Lunar month number
     * @param integer $l_year   Lunar year number
     * @param integer $l_leap   Lunar leap offset
     * @return boolean
     */
    private function validate(int $l_day, int $l_month, int $l_year, int $l_leap): bool
    {
        // Valid date Lunar day
        if($l_day < 1 || $l_day > 30) {
            throw new \Exception("Invalid Lunar day format. The value must be from 1 through 30, your value is: $l_day.");
        }

        // Validate Lunar month
        if($l_month < 1 || $l_month > 12) {
            throw new \Exception("Invalid Lunar month format. The value must be from 1 through 12, your value is: $l_month.");
        }

        // Validate Lunar year
        if($l_year < 999 || $l_year > 9999) {
            throw new \Exception("Invalid Lunar year format. The value must be from 999 through 9999, your value is: $l_month.");
        }

        // Validate Leap year check
        if($l_leap < 0 || $l_leap > 1) {
            throw new \Exception("Invalid Lunar leap month offset format. The value must be 0 or 1, your value is: $l_month.");
        }

        return true;
    }

    /**
     * Convert Lunar date time DateTime object
     *
     * @param integer $l_day    Lunar day number
     * @param integer $l_month  Lunar month number
     * @param integer $l_year   Lunar year number
     * @param integer $l_leap   Lunar leap offset
     * @param float $timeZone   TimeZone by float
     * @return DateTime
     */
    public function lunarDateTimeToGregorian(int $l_day, int $l_month, int $l_year, int $l_leap, float $timeZone): array
    {
        $this->validate($l_day, $l_month, $l_year, $l_leap);

        if($l_month < 11) {
            $a11 = $this->getJdLunarNovemberNewMoon($l_year - 1, $timeZone);
            $b11 = $this->getJdLunarNovemberNewMoon($l_year, $timeZone);
        }
        else {
            $a11 = $this->getJdLunarNovemberNewMoon($l_year, $timeZone);
            $b11 = $this->getJdLunarNovemberNewMoon($l_year + 1, $timeZone);
        }

        $k      = floor(0.5 + ($a11 - 2415021.076998695) / 29.530588853);
        $off    = $l_month - 11;

        if($off < 0) {
            $off += 12;
        }

        if ($b11 - $a11 > 365) {
            $leapOff    = $this->getLeapMonthOffset($a11, $timeZone);
            $leapMonth  = $leapOff - 2;
            $leapCheck  = $l_leap;

            if ($leapCheck < 0) {
                    $leapCheck += 12;
            }
            if ($leapCheck != 0 && $l_month != $leapMonth) {
                throw new \Exception("Unknow Error!");
                
            } 
            else if ($leapCheck != 0 || $off >= $leapOff) {
                $off += 1;
            }
        }
        
        $monthStart = $this->getJdNewMoon($k + $off, $timeZone);
        $gregorian  = $this->jdToGregorian($monthStart + $l_day - 1); 

        return $gregorian;
    }
}