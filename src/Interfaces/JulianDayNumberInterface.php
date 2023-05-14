<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một mốc ngày Julian hỗ trợ độ lệch giờ địa phương
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface JulianDayNumberInterface
{
    /**
     * Số ngày JD tại thời điểm 1970-01-01T00:00:00+0000
     */
    public const EPOCH_JD = 2440587.5;

    /**
     * Trả về số ngày JD chính xác tại thời điểm nhập tương ứng với UTC
     * 
     * @return float 
     */
    public function getJd(): float;

    /**
     * Trả về số ngày JD tại thời điểm nửa đêm (00:00) theo giờ địa phương
     * @return float 
     */
    public function getMidnightJd(): float;

    /**
     * Trả về phần bù thời gian chênh lệch so với UTC
     * @return int 
     */
    public function getOffset(): int;
}