<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\DateTimeFormatter;
use LunarCalendar\Formatter\LunarDateTimeFormatter;

class LunarDateTimeConverter extends AbstractGregorianConverter
{
    protected $lunarDatetime;

    /**
     * Quick get an output object format
     *
     * @param DateTimeFormatter $datetime
     * @return LunarDateTimeFormatter
     */
    public static function create(DateTimeFormatter $datetime): LunarDateTimeFormatter
    {
        $converter = new LunarDateTimeConverter($datetime);
        return $converter->datetime();
    }

    protected function _convert(): void
    {
        $this->_setLunarDateTimeOutput();
    }

    /**
     * Check if input time is point of lunar new day begin
     *
     * @return boolean
     */
    protected function _isNewDayBeginTime(): bool
    {
        return ($this->datetime->getTime('H') >= 23)
                        ? true
                        : false;
    }

    /**
     * Get Julian Day Count from input date time
     *
     * @return float
     */
    protected function _getJdFromInput(): float
    {
        $converter = new GregorianToJulianConverter($this->datetime);
        $jd        = $converter->getJd();

        if($this->_isNewDayBeginTime()) {
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
     * @return integer
     */
    protected function _getJdNewMoon(int|float $k) {
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
        return floor($JdNew + 0.5 + $this->datetime->getTimeZone() / 24);
    }

    /** 
     * Get Julian days point of Lunar 'November' new moon
     * 
     * @param  int $Y  Gregorian year with 4 digits. Ex: 1999, 2021...
     * @return int
     */
    protected function _getJdLunarNovemberNewMoon(int $Y) {
        $inputDateTime = DateTimeFormatter::create()->setDate(31, 12, $Y);
        $slConverter   = new SunlongitudeConverter($inputDateTime);
        
        $jd           = $slConverter->getJd(false);
        $off          = $jd - 2415021;
        $k            = floor($off / 29.530588853);
        $nm           = $this->_getJdNewMoon($k);
        $sunlongitude = floor($slConverter->setJd($nm)->getSunlongitude() / 30);

        if ($sunlongitude >= 9) {
            $nm = $this->_getJdNewMoon($k-1);
        }

        return $nm;
    }

    /**
     * Get Lunar leap month
     *
     * @param integer $a11
     * @return integer
     */
    protected function _getLeapMonthOffset($a11) {
        $slConverter = new SunlongitudeConverter($this->datetime);
        $k           = floor(($a11 - 2415021.076998695) / 29.530588853 + 0.5);
        $last        = 0;
        $i           = 1; // We start with the month following lunar month 11
        $arc         = floor($slConverter->setJd($this->_getJdNewMoon($k + $i))->getSunlongitude() / 30);
        
        do {
            $last    = $arc;
            $i       = $i + 1;
            $arc     = floor($slConverter->setJd($this->_getJdNewMoon($k + $i))->getSunlongitude() / 30);
        } while ($arc != $last && $i < 14);

        return $i - 1;
    }

    /**
     * Convert Solar to Lunar and store result
     *
     * @return void
     */
    protected function _setLunarDateTimeOutput(): void
    {
        $inputJd    = $this->_getJdFromInput();
        $dayNumber  = floor($inputJd);
        $k          = floor(($dayNumber - 2415021.076998695) / 29.530588853);
        $monthStart = $this->_getJdNewMoon($k + 1);

        if ($monthStart > $dayNumber) {
            $monthStart = $this->_getJdNewMoon($k);
        }

        $a11 = $this->_getJdLunarNovemberNewMoon($this->datetime->getDate('Y'));
        $b11 = $a11;

        if ($a11 >= $monthStart) {
            $lunarYear = $this->datetime->getDate('Y');
            $a11 = $this->_getJdLunarNovemberNewMoon($this->datetime->getDate('Y') - 1);
        } else {
            $lunarYear = $this->datetime->getDate('Y') + 1;
            $b11 = $this->_getJdLunarNovemberNewMoon($this->datetime->getDate('Y') + 1);
        }

        $lunarDay   = $dayNumber - $monthStart + 1;
        $diff       = floor(($monthStart - $a11)/29);
        $lunarLeap  = false;
        $lunarMonth = $diff + 11;

        if ($b11 - $a11 > 365) {
            $leapMonthDiff = $this->_getLeapMonthOffset($a11);

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

        $this->lunarDatetime = LunarDateTimeFormatter::create()
            ->setDate((int)$lunarDay, (int)$lunarMonth, (int)$lunarYear)
            ->setTime($this->datetime->getTime('H'), $this->datetime->getTime('i'), $this->datetime->getTime('s'))
            ->setTimeZone($this->datetime->getTimeZone())
            ->setLeapmonth($lunarLeap)
            ->setJd($inputJd);
    }

    public function datetime(): LunarDateTimeFormatter
    {
        if(null === $this->lunarDatetime) {
            $this->_convert();
        }
        
        return $this->lunarDatetime;
    }
}