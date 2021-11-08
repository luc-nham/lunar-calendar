<?php declare(strict_types=1);

namespace LunarCalendar;

use LunarCalendar\Converter\Traits\GregorianToJulian;
use LunarCalendar\Converter\Traits\JulianToGregorian;
use LunarCalendar\Converter\Traits\JulianToSunlongitude;
use LunarCalendar\Formatter\SolarTerm as BaseSolarTerm;

class SolarTerm extends \DateTime
{
    use GregorianToJulian, JulianToGregorian, JulianToSunlongitude ;

    protected $jd;
    protected $sunlongitude;

    public function __construct(string $datetime = "now", ?\DateTimeZone $timezone = null)
    {
        parent::__construct($datetime, $timezone);
        $this->init();
    }

    public function init(): self
    {
        $this->jd = $this->gregorianToJd(
            (int)$this->format('d'),
            (int)$this->format('m'),
            (int)$this->format('Y'),
            (int)$this->format('H'),
            (int)$this->format('i'),
            (int)$this->format('s'),
        );

        $this->sunlongitude = $this->jdToSunlongitude($this->jd, $this->getOffset() / 3600);

        return $this;
    }

    public function getTerm(): BaseSolarTerm
    {
        if(null == $this->jd || null == $this->sunlongitude) {
            $this->init();
        }

        $offset = floor($this->sunlongitude / 15);
        $term   = new BaseSolarTerm((int)$offset);
        
        return $term->setAttrsByOffset();
    }

    public function getDateTimeBegin(): \DateTime
    {
        if(null == $this->jd || null == $this->sunlongitude) {
            $this->init();
        }

        $clone = clone $this;
        $clone->setTime(0, 0, 0);
        
        $timezone       = $this->getOffset() / 3600;
        $prevJdDay      = floor($this->jd);
        $timestamp      = $clone->getTimestamp();
        $preLongitude   = $this->sunlongitude;
        $longitudeBegin = $this->getTerm()->getOffset() * 15;

        if($longitudeBegin < 15) {
            while($preLongitude < 15) {
                $prevJdDay      -= 1;
                $timestamp      -= 86400;
                $preLongitude    = $this->jdToSunlongitude($prevJdDay, $timezone);
            }
        } 
        else {
            while($preLongitude >= $longitudeBegin) {
                $prevJdDay      -= 1;
                $timestamp      -= 86400;
                $preLongitude    = $this->jdToSunlongitude($prevJdDay, $timezone);
            }
        }

        // Get time start new Term (Using Hours only)
        $jdEachHours = 1/24;

        do {
            $prevJdDay   += $jdEachHours;
            $timestamp   += 3600;
            $preLongitude = $this->jdToSunlongitude($prevJdDay, $timezone);
        } 
        while (($longitudeBegin < 15 && $preLongitude > 15) || ($longitudeBegin > 15 && $preLongitude < $longitudeBegin));

        return $clone->setTimestamp($timestamp);
    }

    public function modify($modifier)
    {
        $obj = parent::modify($modifier);
        $this->init();

        return $obj;
    }
}