<?php namespace VanTran\LunarCalendar\Interfaces;

use DateTimeZone;

/**
 * Giao diện xác định kho chứa các dữ kiện Dương lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface DateTimeStorageInterface
{
    /**
     * Năm tối thiểu được hỗ trợ
     */
    public const MIN_YEAR = -4713;

    /**
     * Năm tối đa được hỗ trợ
     */
    public const MAX_YEAR = 2500;

    /**
     * Trả về năm gồm 4 chữ số
     * @return int 
     */
    public function getYear(): int;

    /**
     * Trả về tháng từ 1 đến 12
     * @return int 
     */
    public function getMonth(): int;

    /**
     * Trả về ngày từ 1 đến 31
     * @return int 
     */
    public function getDay(): int;

    /**
     * Trả về giờ từ 0 đến 23
     * @return int 
     */
    public function getHour(): int;

    /**
     * Trả về phút từ 0 đến 59
     * @return int 
     */
    public function getMinute(): int;

    /**
     * Trả về giây từ 0 đến 59
     * @return int 
     */
    public function getSecond(): int;

    /**
     * Trả về phần bù UTC, tính bằng giây
     * @return int 
     */
    public function getOffset(): int;

    /**
     * Trả về múi giờ địa phương
     * @return null|DateTimeZone 
     */
    public function getTimezone(): ?DateTimeZone;
}