<?php namespace VanTran\LunarCalendar\Interfaces;

use DateTimeZone;
use VanTran\LunarCalendar\Interfaces\FormatterInterface;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Giao diện xác định một mốc thời gian Âm lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarDateTimeInteface extends JulianDayNumberInterface, FormatterInterface
{
    /**
     * Trả về múi giờ địa phương
     * @return null|DateTimeZone 
     */
    public function getTimezone(): ?DateTimeZone;

    /**
     * Trả về Độ lệch múi giờ tính bằng giây
     * @return int 
     */
    public function getOffset(): int;

    /**
     * Trả về thời gian Unix
     * @return int 
     */
    public function getTimestamp(): int;
}