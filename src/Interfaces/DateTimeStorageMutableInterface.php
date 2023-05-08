<?php namespace VanTran\LunarCalendar\Interfaces;

use DateTimeZone;

/**
 * Giao diện xác định vùng chứa các dữ kiện Dương lịch có thể điều chỉnh được
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface DateTimeStorageMutableInterface extends DateTimeStorageInterface
{
    /**
     * Cho phép thay đổi năm
     * @param int $year 
     * @return void 
     */
    public function setYear(int $year): void;

    /**
     * Cho phép thay đổi tháng
     * @param int $year 
     * @return void 
     */
    public function setMonth(int $minth): void;

    /**
     * Cho phép thay đổi ngày
     * @param int $year 
     * @return void 
     */
    public function setDay(int $day): void;

    /**
     * Cho phép thay đổi số giờ
     * @param int $year 
     * @return void 
     */
    public function setHour(int $hour): void;

    /**
     * Cho phép thay đổi số phút
     * @param int $year 
     * @return void 
     */
    public function setMinute(int $minute): void;

    /**
     * Cho phép thay đổi số giây
     * @param int $year 
     * @return void 
     */
    public function setSecond(int $second): void;

    /**
     * Cho phép thay đổi chênh lệch giờ địa phương
     * @param int $year 
     * @return void 
     */
    public function setOffset(int $offset): void;

    /**
     * Cho phép thay đổi múi giờ địa phương
     * @param int $year 
     * @return void 
     */
    public function setTimeZone(DateTimeZone $timezone): void;
}