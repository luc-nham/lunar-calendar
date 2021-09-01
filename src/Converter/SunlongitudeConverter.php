<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

class SunlongitudeConverter extends GregorianToJulianConverter
{
    protected $sunlongitude;

    /**
     * This Setter allow object converts output derectly from a Julian Day Count
     *
     * @param float $jd
     * @return SunlongitudeConverter
     */
    public function setJd(float $jd): SunlongitudeConverter
    {
        $this->jd = $jd;
        $this->_convert();
        
        return $this;
    }

    public function getSunlongitude($includeDecimal = true): float
    {
        $result = $this->sunlongitude;

        return ($includeDecimal)
                    ? $result
                    : floor($result);
    }

    protected function _convert(): void
    {
        if(null == $this->jd) {
            parent::_convert();
        }

        $T      = ($this->jd - 2451545.5 - $this->datetime->getTimeZone() / 24) / 36525;
        $dr     = M_PI / 180;
        $L      = 280.460 + 36000.770 * $T;
        $G      = 357.528 + 35999.050 * $T;
        $ec     = 1.915 * sin($dr *$G) + 0.020 * sin($dr *2*$G);
        $lambda = $L + $ec ;
    
        $this->sunlongitude =  $lambda - 360 * (floor($lambda / (360)));

        // A more Algorithm
        // $T      = ($this->jd - 2451545.5 - $this->datetime->getTimeZone() / 24) / 36525; 
        // $T2     = $T * $T;
        // $dr     = M_PI/180;
        // $M      = 357.52910 + 35999.05030*$T - 0.0001559*$T2 - 0.00000048*$T*$T2;
        // $L0     = 280.46645 + 36000.76983*$T + 0.0003032*$T2;
        // $DL     = (1.914600 - 0.004817*$T - 0.000014*$T2)*sin($dr*$M);
        // $DL    += (0.019993 - 0.000101*$T) * sin($dr * 2 * $M) + 0.000290 * sin($dr * 3 * $M);
        // $L      = $L0 + $DL;
        // $omega  = 125.04 - 1934.136 * $T;
        // $L     -= 0.00569 - 0.00478 * sin($omega * $dr);
        // $L     *= $dr;
        // $L     -= M_PI*2*(floor($L/(M_PI*2))); // Normalize to (0, 2*PI);
        
        // $this->sunlongitude = $L / M_PI * 6 * 30;
    }
}