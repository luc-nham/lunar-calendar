<?php declare(strict_types=1);

namespace LunarCalendar\Converter;

use LunarCalendar\Formatter\DateTimeFormatter;
use LunarCalendar\Formatter\SolarTermFormatter;

class SolarTermConverter extends SunlongitudeConverter
{
    protected $term;

    protected function _convert(): void
    {
        if(null === $this->sunlongitude) {
            parent::_convert();
        }
    }

    /**
     * Get Solar term object formated
     *
     * @return SolarTermFormatter
     */
    public function getTerm(): SolarTermFormatter
    {
        if(null === $this->term) {
            $this->term = new SolarTermFormatter();
            $this->term->create($this->getSunlongitude());
        }
        
        return $this->term;
    }

    public function getDateTimeBegin(): DateTimeFormatter
    {
        $prevJdDay      = floor($this->jd);
        $preLongitude   = $this->sunlongitude;
        $longitudeBegin = $this->getTerm()->getSunlongitude();

        $slConverter  = new SunlongitudeConverter($this->datetime);

        if($longitudeBegin < 15) {
            while($preLongitude < 15) {
                $prevJdDay      -= 1;
                $preLongitude    = $slConverter->setJd($prevJdDay)->getSunlongitude();
            }
        } 
        else {
            while($preLongitude >= $longitudeBegin) {
                $prevJdDay      -= 1;
                $preLongitude    = $slConverter->setJd($prevJdDay)->getSunlongitude();
            }
        }

        // Get time start new Term (Using Hours only)
        $jdEachHours = 1/24;

        do {
            $prevJdDay   += $jdEachHours;
            $preLongitude = $slConverter->setJd($prevJdDay)->getSunlongitude();
        } 
        while (($longitudeBegin < 15 && $preLongitude > 15) || ($longitudeBegin > 15 && $preLongitude < $longitudeBegin));

        // Convert back jd to date time
        $datetime = (new JulianToGregorianConverter($prevJdDay))->getDateTime();
        $datetime->setTimeZone($this->datetime->getTimeZone());

        return $datetime;
    }
}