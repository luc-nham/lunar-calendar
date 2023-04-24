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
        if ($this->getOffset() === self::UTC_OFFSET) {
            return floor($this->getJd());
        }

        $decimal = 1 - $this->getOffset() / 86400;
        $utcMidnight = floor($this->getJd());

        if ($this->getJd() >= $utcMidnight + $decimal) {
            $midnight = $utcMidnight + $decimal;
        }
        else {
            $midnight = $utcMidnight + $decimal - 1;
        }

        return $midnight;
    }
}