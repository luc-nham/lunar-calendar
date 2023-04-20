<?php namespace VanTran\LunarCalendar\MoonPhases;

interface Lunar11thNewMoonPhaseInterface extends NewMoonPhaseInterface
{
    /**
     * Trả về năm Âm lịch gồm 4 chữ số
     * @return int 
     */
    public function getYear(): int;
}