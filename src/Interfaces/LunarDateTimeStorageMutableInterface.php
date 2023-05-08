<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định kho chứa thời gian Âm lịch với các dữ liệu có thể điều chỉnh được
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarDateTimeStorageMutableInterface extends LunarDateTimeStorageInterface, DateTimeStorageMutableInterface
{
    /**
     * Cho phép thay đổi giá trị xác định tháng nhuận
     * 
     * @param bool $isLeap 
     * @return void 
     */
    public function setIsLeapMonth(bool $isLeap): void;
}