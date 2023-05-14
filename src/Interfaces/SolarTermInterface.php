<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một đối tượng Tiết hoặc Khí trong nhóm Tiết khí (Solar Term)
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface SolarTermInterface extends SunlongitudeMutableInterface, TermInterface
{
    /**
     * Trả về vị trí bắt đầu của Tiết hoặc Khí (điểm khởi)
     * 
     * @return SolarTermInterface 
     */
    public function begin(): SolarTermInterface;

    /**
     * Trả về điểm bắt đầu của tiết khí tiếp theo (chưa đến).
     * 
     * @param int $term Số lượng tiết khí tiếp theo, mỗi 1 đơn vị cách nhau 15 độ
     * @return SolarTermInterface 
     */
    public function next(int $term = 1): SolarTermInterface;

    /**
     * Trả về  điểm bắt đầu của tiết khí trước đó (đã qua)
     * 
     * @param int $term Số lượng tiết khí trước đó, mỗi 1 đơn vị cách nhau 15 độ
     * @return SolarTermInterface 
     */
    public function previuos(int $term = 1): SolarTermInterface;
}