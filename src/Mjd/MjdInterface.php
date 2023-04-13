<?php namespace VanTran\LunarCalendar\Mjd;

interface MjdInterface
{
    /**
     * Phần bù thời gian tương ứng với UTC (tính bằng giây)
     */
    public const UTC_OFFSET = 0;

    /**
     * Phần bù chênh lệch so với UTC tại Việt Nam, tính bằng giây (múi giờ GMT+7). Giá trị này được lấy theo tiêu chuẩn
     * của múi giờ quốc tế mà không sử dụng giá trị lịch sử có liên quan đến yếu tố địa chính trị.
     */
    public const VN_OFFSET = 25200;

    /**
     * Số ngày MJD tại thời điểm 1970-01-01T00:00:00+0000
     */
    public const EPOCH_MJD = 2440588;

    /**
     * Trả về số ngày MJD chính xác tại thời điểm nhập tương ứng với UTC
     * 
     * @return float 
     */
    public function getJd(): float;

    /**
     * Trả về số ngày MJD tại thời điểm nửa đêm (00:00) theo giờ địa phương
     * @return float 
     */
    public function getMidnightJd(): float;

    /**
     * Trả về phần bù thời gian chênh lệch so với UTC
     * @return int 
     */
    public function getOffset(): int;
}