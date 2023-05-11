<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện triển khai cho các lớp xử lý Kinh độ Mặt trời
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Sunlongitude
 */
interface SunlongitudeInterface extends JulianDayNumberInterface
{
    /**
     * Trả về góc hiện tại, có giá trị từ 0 đến 359.xxx
     * 
     * @param bool $withFragtion Tùy lấy giá trị chính xác bao gồm phần thập phân. Mặc định false sẽ làm tròn kết quả trả
     *                          về (120,3456 sẽ trả về 120).
     * @return int|float 
     */
    public function getDegrees(bool $withFragtion = false): int|float;

    /**
     * Trả về góc KDMT tại điểm nửa đêm theo giờ địa phương
     * 
     * @param bool $withFragtion 
     * @return int|float 
     */
    public function getMidnightDegrees(bool $withFragtion = false): int|float;
}