<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định các phương thức của điểm bắt đầu tháng nhuận trong 1 năm âm lịch. Lưu ý rằng không phải năm nào
 * cũng có tháng nhuận.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarLeapMonthInterface extends JulianDayNumberInterface
{
    /**
     * Xác định 1 năm Âm lịch có thể nhuận được hay không
     * @return bool 
     */
    public function isLeap(): bool;

    /**
     * Trả về vị trí tháng nhuận trong năm, chẳng hạn năm 2033 nhuận tháng 11, trả về 11.
     * @return null|int 
     */
    public function getMonth(): null|int;
}