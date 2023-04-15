<?php namespace VanTran\LunarCalendar\Sunlongitude;

use VanTran\LunarCalendar\Mjd\GregorianToMjd;

class GregorianToSunlongitude extends MjdToSunlongitude
{
    public function __construct(
        protected int $offset, 
        protected int $year, 
        protected int $month, 
        protected int $day, 
        protected int $hour = 0, 
        protected int $minute = 0,
        protected int $second = 0
    )
    {
        $mjd = new GregorianToMjd(
            $this->offset,
            $this->year,
            $this->month,
            $this->day,
            $this->hour,
            $this->minute,
            $this->second
        );

        parent::__construct($mjd);
    }
}