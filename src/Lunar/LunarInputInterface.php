<?php namespace VanTran\LunarCalendar\Lunar;

/**
 * Giao diện xác định đối tượng triển khai có thể truy cập được các mốc ngày tháng trong Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
interface LunarInputInterface
{
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
}