<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;

/**
 * Giao diện xác định đối tượng triển khai có thể truy cập được các mốc ngày tháng trong Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
interface LunarInputInterface
{
    /**
     * Năm tối thiểu được hỗ trợ
     */
    public const MIN_YEAR = 1901;

    /**
     * Năm tối đa được hỗ trợ
     */
    public const MAX_YEAR = 2100;

    /**
     * Độ lệch múi giờ cho Âm lịch Việt Nam
     */
    public const VN_OFFSET = 25200;

    /**
     * Múi giờ địa phương mặc định cho Âm lịch Việt Nam.
     */
    public const VN_TIMEZONE = '+0700';

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
     * Xác định tháng Âm lịch có phải là tháng nhuận không
     * @return bool 
     */
    public function isLeapMonth(): bool;

    /**
     * Trả về múi giờ địa phương
     * @return null|DateTimeZone 
     */
    public function getTimezone(): ?DateTimeZone;
}