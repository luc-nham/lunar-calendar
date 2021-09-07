<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\LunarDateTimeStorageFormatter;
use LunarCalendar\Formatter\LunarDateTimeStorageInterface;

class GregorianToLunarConverter
{
    use GregorianToJulian, JulianToSunlongitude;

    private $d;
    private $m;
    private $Y;
    private $H;
    private $i;
    private $s;
    private $timeZone;

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
    public function __construct(int $d, int $m, int $Y, int $H, int $i, int $s, float $timeZone)
    {
        $this->d = $d;
        $this->m = $m;
        $this->Y = $Y;
        $this->H = $H;
        $this->i = $i;
        $this->s = $s;
        $this->timeZone = $timeZone;
    }

    public function getJd(): float
    {
        $jd = $this->gregorianToJd($this->d, $this->m, $this->Y, $this->H, $this->i, $this->s);

        if($this->H == 23) {
            $jd += 1;
        }

        return $jd;
    }

    /**
     * Get Julian days point of new moon.
     *
     * @param integer $k    k follows formula: (j - 2415021) / 29.530588853
     *                          - j: Julian days at the desired time
     *                          - 2415021: Julian days at 1/1/1990 Gregorian
     *                          - 29.530588853: Moon orbit
     * @param float   $timeZone
     * @return integer
     */
    protected function getJdNewMoon(int|float $k) 
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
        return floor($JdNew + 0.5 + $this->timeZone / 24);
    }

    /** 
     * Get Julian days point of Lunar 'November' new moon
     * 
     * @param  int $Y  Gregorian year with 4 digits. Ex: 1999, 2021...
     * @return int
     */
    protected function getJdLunarNovemberNewMoon(int $Y) {
        $jd           = $this->gregorianToJd(31, 12, $Y);
        $off          = $jd - 2415021;
        $k            = floor($off / 29.530588853);
        $nm           = $this->getJdNewMoon($k);
        $sunlongitude = floor($this->jdToSunlongitude($nm, $this->timeZone) / 30);

        if ($sunlongitude >= 9) {
            $nm = $this->getJdNewMoon($k-1);
        }

        return $nm;
    }

    /**
     * Get Lunar leap month
     *
     * @param integer $a11
     * @return integer
     */
    protected function getLeapMonthOffset($a11) {
        $k           = floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $last        = 0;
        $i           = 1; // We start with the month following lunar month 11
        $arc         = floor($this->jdToSunlongitude($this->getJdNewMoon($k + $i), $this->timeZone) / 30);
        
        do {
            $last    = $arc;
            $i       = $i + 1;
            $arc     = floor($this->jdToSunlongitude($this->getJdNewMoon($k + $i), $this->timeZone) / 30);
        } while ($arc != $last && $i < 14);

        return $i - 1;
    }

    /**
     * Convert Solar to Lunar and store result
     *
     * @return void
     */
    public function output(): LunarDateTimeStorageInterface
    {
        $inputJd    = $this->getJd();
        $dayNumber  = floor($inputJd);
        $k          = floor(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = $this->getJdNewMoon($k + 1);

        if ($monthStart > $dayNumber) {
            $monthStart = $this->getJdNewMoon($k);
        }

        $a11 = $this->getJdLunarNovemberNewMoon($this->Y);
        $b11 = $a11;

        if ($a11 >= $monthStart) {
            $lunarYear = $this->Y;
            $a11 = $this->getJdLunarNovemberNewMoon($this->Y - 1);
        } else {
            $lunarYear = $this->Y + 1;
            $b11 = $this->getJdLunarNovemberNewMoon($this->Y + 1);
        }

        $lunarDay   = $dayNumber - $monthStart + 1;
        $diff       = floor(($monthStart - $a11)/29);
        $lunarLeap  = false;
        $lunarMonth = $diff + 11;

        if ($b11 - $a11 > 365) {
            $leapMonthDiff = $this->getLeapMonthOffset($a11);

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

        return LunarDateTimeStorageFormatter::create()
            ->set('d', (int)$lunarDay)
            ->set('m', (int)$lunarMonth)
            ->set('Y', (int)$lunarYear)
            ->set('H', $this->H)
            ->set('i', $this->i)
            ->set('s', $this->s)
            ->set('o', $this->timeZone)
            ->set('l', (int)$lunarLeap)
            ->set('j', $inputJd);
    }
}