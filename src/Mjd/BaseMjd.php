<?php namespace VanTran\LunarCalendar\Mjd;

class BaseMjd implements MjdInterface
{
    /**
     * Tạo đối tượng mới
     * 
     * @param float $jd Số ngày Julian
     * @param int $offset Phần bù chênh lệch giờ địa phương so với UTC, tính bằng giây
     * @return void 
     */
    public function __construct(protected float $jd, protected int $offset = self::VN_OFFSET) {}

    /**
     * {@inheritdoc}
     * @return int 
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
     * @return float 
     */
    public function getMidnightJd(): float 
    { 
        if ($this->offset === self::UTC_OFFSET) {
            return floor($this->jd);
        }

        $decimal = 1 - $this->offset / 86400;
        $utcMidnight = floor($this->jd);

        if ($this->jd >= $utcMidnight + $decimal) {
            $midnight = $utcMidnight + $decimal;
        }
        else {
            $midnight = $utcMidnight + $decimal - 1;
        }

        return $midnight;
    }
}