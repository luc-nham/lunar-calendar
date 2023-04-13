<?php namespace VanTran\LunarCalendar\Mjd;

/**
 * Bộ chuyển đổi một mốc thời gian UNIX thành số ngày MJD
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Mjd
 */
class UnixToMjd extends BaseMjd
{
    /**
     * Tạo đối tượng mới
     * 
     * @param int $timestamp Tem thời gian Unix
     * @param int $offset  Phần bù chênh lệch giờ địa phương so với UTC, tính bằng giây
     * @return void 
     */
    public function __construct(int $timestamp, int $offset = self::VN_OFFSET)
    {
        parent::__construct(
            $this->getJdFromTimestamp($timestamp), 
            $offset
        );
    }

    /**
     * Chuyển đổi một mốc tem thời gian Unix thành số ngày MJD
     * @param mixed $timestamp 
     * @return float 
     */
    protected function getJdFromTimestamp($timestamp): float
    {
        return $timestamp / 86400 + self::EPOCH_MJD;
    }
}