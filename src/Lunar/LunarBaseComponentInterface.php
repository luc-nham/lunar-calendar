<?php namespace VanTran\LunarCalendar\Lunar;

use VanTran\LunarCalendar\Mjd\MjdInterface;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\MoonPhaseInterface;

interface LunarBaseComponentInterface
{
    /**
     * Trả về điểm mốc MJD tương ứng với thời điểm Âm lịch
     * @return MjdInterface 
     */
    public function getMjd(): MjdInterface;

    /**
     * Trả về điểm Sóc tương ứng với thời điểm Âm lịch
     * @return NewMoonPhaseInterface 
     */
    public function getNewMoon(): MoonPhaseInterface;

    /**
     * Trả về điểm Sóc tháng 11 Âm lịch của năm Âm lịch
     * @return Lunar11thNewMoonPhaseInterface 
     */
    public function get11thNewMoon(): Lunar11thNewMoonPhaseInterface;

    /**
     * Trả về tháng nhuận Âm lịch, 1 năm có thể nhuận hoặc không
     * @return null|LunarLeapMonthInterface 
     */
    public function getLeapMonth(): null|LunarLeapMonthInterface;
}