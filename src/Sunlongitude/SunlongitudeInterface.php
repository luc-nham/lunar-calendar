<?php namespace VanTran\LunarCalendar\Sunlongitude;

use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Giao diện triển khai cho các lớp xử lý Kinh độ Mặt trời
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Sunlongitude
 */
interface SunlongitudeInterface extends MjdInterface
{
    /**
     * Trả về góc hiện tại, có giá trị từ 0 đến 359.xxx
     * 
     * @param bool $withDecimal Tùy lấy giá trị chính xác bao gồm phần thập phân. Mặc định false sẽ làm tròn kết quả trả
     *                          về (120,3456 sẽ trả về 120).
     * @return int|float 
     */
    public function getDegrees(bool $withDecimal = false): int|float;

    /**
     * Trả về góc KDMT tại thời điểm nửa đêm, có giá trị từ 0 đến 359.xxx
     * 
     * @param bool $withDecimal Tùy lấy giá trị chính xác bao gồm phần thập phân. Mặc định false sẽ làm tròn kết quả trả
     *                          về (120,3456 sẽ trả về 120).
     * @return int|float 
     */
    public function getMidnightDegrees(bool $withDecimal = false): int|float;
}