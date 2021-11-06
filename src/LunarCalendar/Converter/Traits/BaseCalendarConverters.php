<?php declare(strict_types=1);

namespace LunarCalendar\Converter\Traits;

/**
 * This trait includes methods which used by booth main features:
 *  - Convert Gregorian date time to Lunar date time
 *  - Convert Lunar date time to Gregorian date time
 * 
 * @author Van Tran <caovan.info@gmail.com>
 */
trait BaseCalendarConverters
{
    use JulianToSunlongitude;

    /**
     * Get Julian days point of new moon.
     *
     * @param integer $k    k follows formula: (j - 2415021) / 29.530588853
     *                          - j: Julian days at the desired time
     *                          - 2415021: Julian days at 1/1/1990 Gregorian
     *                          - 29.530588853: Moon orbit days
     * @param integer|float   $timezone
     * @return integer
     */
    protected function getJdNewMoon(int|float $k, int|float $timezone): int|float
    {
        $T      = $k/1236.85; // Time in Julian centuries from 1900 January 0.5
        $T2     = $T * $T;
        $T3     = $T2 * $T;
        $dr     = M_PI/180;
        $Jd1    = 2415020.75933 + 29.53058868*$k + 0.0001178*$T2 - 0.000000155*$T3;
        $Jd1    = $Jd1 + 0.00033*sin((166.56 + 132.87*$T - 0.009173*$T2)*$dr); // Mean new moon
        $M      = 359.2242 + 29.10535608*$k - 0.0000333*$T2 - 0.00000347*$T3; // Sun's mean anomaly
        $Mpr    = 306.0253 + 385.81691806*$k + 0.0107306*$T2 + 0.00001236*$T3; // Moon's mean anomaly
        $F      = 21.2964 + 390.67050646*$k - 0.0016528*$T2 - 0.00000239*$T3; // Moon's argument of latitude
        $C1     =(0.1734 - 0.000393*$T)*sin($M*$dr) + 0.0021*sin(2*$dr*$M);
        $C1     = $C1 - 0.4068*sin($Mpr*$dr) + 0.0161*sin($dr*2*$Mpr);
        $C1     = $C1 - 0.0004*sin($dr*3*$Mpr);
        $C1     = $C1 + 0.0104*sin($dr*2*$F) - 0.0051*sin($dr*($M+$Mpr));
        $C1     = $C1 - 0.0074*sin($dr*($M-$Mpr)) + 0.0004*sin($dr*(2*$F+$M));
        $C1     = $C1 - 0.0004*sin($dr*(2*$F-$M)) - 0.0006*sin($dr*(2*$F+$Mpr));
        $C1     = $C1 + 0.0010*sin($dr*(2*$F-$Mpr)) + 0.0005*sin($dr*(2*$Mpr+$M));

        if ($T < -11) {
            $deltat= 0.001 + 0.000839*$T + 0.0002261*$T2 - 0.00000845*$T3 - 0.000000081*$T*$T3;
        } else {
            $deltat= -0.000278 + 0.000265*$T + 0.000262*$T2;
        };

        $JdNew = $Jd1 + $C1 - $deltat;
        return floor($JdNew + 0.5 + $timezone / 24);
    }

    /** 
     * Get Julian days point of Lunar 'November' new moon
     * 
     * @param  int $Y  Gregorian year with 4 digits. Ex: 1999, 2021..
     * @param integer|float   $timezone
     * @return int
     */
    protected function getJdLunarNovemberNewMoon(int $Y, int|float $timezone) {
        $jd           = $this->gregorianToJd(31, 12, $Y);
        $off          = $jd - 2415021;
        $k            = floor($off / 29.530588853);
        $nm           = $this->getJdNewMoon($k, $timezone);
        $sunlongitude = floor($this->jdToSunlongitude($nm, $timezone) / 30);

        if ($sunlongitude >= 9) {
            $nm = $this->getJdNewMoon($k-1, $timezone);
        }

        return $nm;
    }

    /**
     * Get Lunar leap month
     *
     * @param integer $a11
     * @return integer
     */
    protected function getLeapMonthOffset(int $a11, int|float $timezone) {
        $k           = floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $last        = 0;
        $i           = 1; // We start with the month following lunar month 11
        $arc         = floor($this->jdToSunlongitude($this->getJdNewMoon($k + $i, $timezone), $timezone) / 30);
        
        do {
            $last    = $arc;
            $i       = $i + 1;
            $arc     = floor($this->jdToSunlongitude($this->getJdNewMoon($k + $i, $timezone), $timezone) / 30);
        } while ($arc != $last && $i < 14);

        return $i - 1;
    }
}