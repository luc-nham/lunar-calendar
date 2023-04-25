<?php namespace VanTran\LunarCalendarCli\Traits;

use DateTime;
use DateTimeZone;

/**
 * Chuyển đổi một mốc ngày MJD thành đối tượng PHP DateTime
 */
trait JulianToDateTime
{
    use JulianToUnix;

    /**
     * Chuyển đổi một mốc ngày MJD thành đối tượng PHP DateTime
     * 
     * @param int|float $jdn 
     * @param null|DateTimeZone $timezone 
     * @return DateTime 
     */
    public function jdToDateTime(int|float $jdn, ?DateTimeZone $timezone = null): DateTime
    {
        $timestamp = $this->jdToUnix($jdn);
        $date = new DateTime('now', $timezone);
        $date->setTimestamp($timestamp);

        return $date;
    }
}