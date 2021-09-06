<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

trait JulianToSunlongitude
{
    public function jdToSunlongitude(float $jd, float $timeZone): float
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