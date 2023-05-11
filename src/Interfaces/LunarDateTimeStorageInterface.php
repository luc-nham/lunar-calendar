<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định kho chứa các dữ kiện thời gian Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarDateTimeStorageInterface extends DateTimeStorageInterface
{
    /**
     * Xác định tháng Âm lịch có phải là tháng nhuận không
     * @return bool 
     */
    public function isLeapMonth(): bool;
}