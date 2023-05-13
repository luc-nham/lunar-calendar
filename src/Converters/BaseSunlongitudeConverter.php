<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\SunlongitudeInterface;

/**
 * Bộ chuyển đổi cơ sở Kinh độ Mặt trời từ 1 mốc ngày Julian.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Sunlongitude
 */
class BaseSunlongitudeConverter extends BaseJDN implements SunlongitudeInterface
{
    /**
     * Tạo đối tượng mới
     * 
     * @param float $jd Số ngày Julian
     * @param int $offset Bù UTC, tính bằng giây
     * @return void 
     */
    public function __construct(float $jd, int $offset = 0)
    {
        parent::__construct($jd, $offset);
    }

    /**
     * Làm tròn hoặc giữ kết quả đầu ra
     * 
     * @param float $degrees 
     * @param bool $withFragtion 
     * @return int|float 
     */
    private function output(float $degrees, bool $withFragtion): int|float
    {
        return ($withFragtion)
            ? $degrees
            : floor($degrees);
    }

    /**
     * {@inheritdoc}
     */
    public function getMidnightDegrees(bool $withFragtion = false): int|float 
    { 
        $degrees = $this->getDegreesFromJd($this->getMidnightJd());

        return $this->output(
            $degrees,
            $withFragtion
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDegrees(bool $withFragtion = false): int|float 
    { 
        $degrees = $this->getDegreesFromJd($this->jd);

        return $this->output(
            $degrees,
            $withFragtion
        );
    }
    
    /**
     * Trả về góc KDMT tương ứng với số ngày Julian cho trước
     * 
     * @param float $jd 
     * @return float 
     */
    final protected function getDegreesFromJd(float $jd): float
    {
        $T = ($jd - 2451545) / 36525;
        $dr = M_PI / 180;
        $L = 280.460 + 36000.770 * $T;
        $G = 357.528 + 35999.050 * $T;
        $ec = 1.915 * sin($dr * $G) + 0.020 * sin($dr * 2 * $G);
        $lambda = $L + $ec ;
        $L =  $lambda - 360 * floor($lambda / 360);

        return round($L, 3);
    }
}