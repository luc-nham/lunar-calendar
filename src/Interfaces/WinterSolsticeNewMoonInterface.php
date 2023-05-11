<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định các phương thức của điểm Sóc tháng 11 tương ứng với năm Âm lịch, tức trong tháng âm lịch đó có 
 * chứa điểm bắt đầu Trung khí Đông chí.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface WinterSolsticeNewMoonInterface extends MoonPhaseInterface
{
    /**
     * Trả về năm Âm lịch gồm 4 chữ số
     * @return int 
     */
    public function getYear(): int;
}