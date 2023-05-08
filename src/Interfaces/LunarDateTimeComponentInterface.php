<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một nhóm các thành phần cơ bản cấu tạo nên ngày tháng Âm lịch, được sử dụng để định dạng đầu ra 
 * hoặc truy xuất dữ liệu.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarDateTimeComponentInterface extends JulianDayNumberInterface
{
    /**
     * Trả về điểm Sóc tương ứng với thời điểm Âm lịch
     * @return MoonPhaseInterface 
     */
    public function getNewMoon(): MoonPhaseInterface;

    /**
     * Trả về điểm Sóc tháng 11 Âm lịch của năm Âm lịch
     * @return WinterSolsticeNewMoonInterface 
     */
    public function getWsNewMoon(): WinterSolsticeNewMoonInterface;

    /**
     * Trả về tháng nhuận Âm lịch, 1 năm có thể nhuận hoặc không
     * @return LunarLeapMonthInterface 
     */
    public function getLeapMonth(): LunarLeapMonthInterface;

    /**
     * Trả về kho chứa các mốc ngày tháng Âm lịch
     * @return LunarDateTimeStorageInterface 
     */
    public function getDateTimeStorage(): LunarDateTimeStorageInterface;

    /**
     * Trả về số ngày trong 1 tháng Âm lịch
     * @return int 
     */
    public function getDayOfMonth(): int;

    /**
     * Trả về số ngày trong năm Âm lịch
     * @return int 
     */
    public function getDayOfYear(): int;
}