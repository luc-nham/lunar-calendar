<?php declare(strict_types=1);

namespace LunarCalendar\Converter\Traits;

/**
 * This trait convert a Julian days count to Sunlongitude degress, which can be
 * use for caculates Solar term.
 * 
 * @author VanTran <caovan.info@gmail.com>
 */
trait JulianToSunlongitude
{
    /**
     * Converts a Julian days count to Sunlongitude degress
     *
     * @param float $jd
     * @param float $timeZone
     * @return float
     */
    public function jdToSunlongitude(int|float $jd, int|float $timeZone): float
    {
        $T      = ($jd - 2451545.5 - $timeZone / 24) / 36525;
        $dr     = M_PI / 180;
        $L      = 280.460 + 36000.770 * $T;
        $G      = 357.528 + 35999.050 * $T;
        $ec     = 1.915 * sin($dr *$G) + 0.020 * sin($dr *2*$G);
        $lambda = $L + $ec ;  
        $sl     =  $lambda - 360 * (floor($lambda / (360)));

        return $sl;
    }
}