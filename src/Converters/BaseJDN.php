<?php namespace VanTran\LunarCalendar\Converters;

use Exception;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Lớp cơ sở JDN
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class BaseJDN implements JulianDayNumberInterface
{
    /**
     * @var mixed Số ngày Julian lúc nửa đêm 00:00 tương ứng với giờ địa phương
     */
    private $midnightJd;

    /**
     * Tạo đối tượng mới
     * 
     * @param float $jd Số ngày Julian
     * @param int $offset Chênh lệch giờ địa phương, tính bằng giây, mặc định 0 tương ứng UTC
     * @return void 
     */
    public function __construct(protected float $jd = self::EPOCH_JD, protected int $offset = 0) {}

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int
    { 
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     * @return float 
     */
    public function getJd(): float 
    { 
        return $this->jd;
    }

    /**
     * {@inheritdoc}
     */
    public function getMidnightJd(): float 
    { 
        if (!$this->midnightJd) {
            $jdn = $this->getJd();
            $diff = $jdn - floor($jdn);

            $utcMidnight = ($diff >= 0.5)
                ? floor($jdn) + 0.5
                : floor($jdn) - 0.5;
        

            if ($this->getOffset() === 0) {
                return $utcMidnight;
            }

            $decimal = 1 - $this->getOffset() / 86400;

            if ($jdn >= $utcMidnight + $decimal) {
                $midnight = $utcMidnight + $decimal;
            }
            else {
                $midnight = $utcMidnight + $decimal - 1;
            }

            $this->midnightJd = round($midnight, 7);
        }
        
        return $this->midnightJd;
    }

    /**
     * Đặt giá trị JDN mới
     * 
     * @param int|float $jdn 
     * @return void 
     */
    public function setJd(int|float $jdn): void
    {
        $this->jd = $jdn;
        $this->midnightJd = null;
    }

    /**
     * Đặt giá trị chênh lệch giờ địa phương mới
     * 
     * @param int $offset Có giá trị từ -43200 đến 50400
     * @return void 
     */
    public function setOffset(int $offset): void
    {
        if ($offset < -43200 || $offset > 50400) {
            throw new Exception("Invalid offset value.");
        }

        if ($this->getOffset() != $offset) {
            $this->offset = $offset;
            $this->midnightJd = null;
        }
    }
}