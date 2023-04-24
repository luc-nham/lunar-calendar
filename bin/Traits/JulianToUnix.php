<?php namespace VanTran\LunarCalendarCli\Traits;

/**
 * Chuyển đổi số ngày MJD thành tem thời gian Unix
 */
trait JulianToUnix
{
    /**
     * Chuyển đổi số ngày MJD thành tem thời gian Unix
     * 
     * @param int|float $jdn 
     * @return int 
     */
    public function jdToUnix(int|float $jdn): int
    {
        $timestamp = ($jdn - 2440588) * 86400;
        return floor($timestamp);
    }
}