<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện mở rộng của Kinh độ Mặt trời, bổ sung khả năng tìm kiếm một góc mới từ vị trí hiện tại
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface SunlongitudeMutableInterface extends SunlongitudeInterface
{
    /**
     * Trả về vị trí Kinh độ mặt trời mới từ số độ cộng thêm (chưa đến)
     * 
     * @param int|float $deg 
     * @return SunlongitudeMutableInterface 
     */
    public function add(int|float $deg): SunlongitudeMutableInterface;

    /**
     * Trả về vị trí Kinh độ Mặt trời mới từ số độ trừ đi (quá khứ)
     * @param int|float $deg 
     * @return SunlongitudeMutableInterface 
     */
    public function subtract(int|float $deg): SunlongitudeMutableInterface;
}