<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;
use VanTran\LunarCalendar\Mjd\MjdInterface;
use VanTran\LunarCalendar\MoonPhases\Lunar11thNewMoonPhaseInterface;
use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;

interface LunarBaseComponentInterface extends MjdInterface
{
    /**
     * Trả về điểm Sóc tương ứng với thời điểm Âm lịch
     * @return NewMoonPhaseInterface 
     */
    public function getNewMoon(): NewMoonPhaseInterface;

    /**
     * Trả về điểm Sóc tháng 11 Âm lịch của năm Âm lịch
     * @return Lunar11thNewMoonPhaseInterface 
     */
    public function get11thNewMoon(): Lunar11thNewMoonPhaseInterface;

    /**
     * Trả về tháng nhuận Âm lịch, 1 năm có thể nhuận hoặc không
     * @return LunarLeapMonthInterface 
     */
    public function getLeapMonth(): LunarLeapMonthInterface;

    /**
     * Trả về số ngày trong 1 tháng Âm lịch
     * @return int 
     */
    public function getDayOfMonth(): int;

    /**
     * Trả về số ngày trong năm Âm lịch
     * @return int 
     */
    public function getDayOfYear(): int;

    /**
     * Trả về múi giờ địa phương
     * @return null|DateTimeZone 
     */
    public function getTimeZone(): ?DateTimeZone;
}