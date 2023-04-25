<?php namespace VanTran\LunarCalendar\Lunar;

use VanTran\LunarCalendar\Mjd\MjdInterface;

interface LunarLeapMonthInterface extends MjdInterface
{
    /**
     * Xác định 1 năm Âm lịch có thể nhuận được hay không
     * @return bool 
     */
    public function isLeap(): bool;

    /**
     * Trả về vị trí tháng nhuận trong năm, chẳng hạn năm 2033 nhuận tháng 11, trả về 11.
     * @return null|int 
     */
    public function getMonth(): null|int;
}