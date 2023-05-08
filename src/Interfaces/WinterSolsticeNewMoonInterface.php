<?php namespace VanTran\LunarCalendar\Interfaces;

interface WinterSolsticeNewMoonInterface extends MoonPhaseInterface
{
    /**
     * Trả về năm Âm lịch gồm 4 chữ số
     * @return int 
     */
    public function getYear(): int;
}