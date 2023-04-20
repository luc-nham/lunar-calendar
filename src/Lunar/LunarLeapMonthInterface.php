<?php namespace VanTran\LunarCalendar\Lunar;

use VanTran\LunarCalendar\MoonPhases\NewMoonPhaseInterface;

interface LunarLeapMonthInterface
{
    /**
     * Xác định có tháng nhuận hay không
     * 
     * @return bool 
     */
    public function isLeap(): bool;

    /**
     * Trả về vị trí tháng nhuận trong năm, chẳng hạn năm 2033 nhuận tháng 11, trả về 11. Trả về false nếu không có 
     * tháng nhuận.
     * 
     * @return false|int 
     */
    public function getMonthOffset(): false|int;

    /**
     * Trả về điểm Sóc (Trăng mới) của tháng nhuận nếu có, hoặc null nếu không.
     * 
     * @return null|NewMoonPhaseInterface 
     */
    public function getNewMoon(): ?NewMoonPhaseInterface;
}