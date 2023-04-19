<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Lớp cơ sở cho tính toán các Pha của một chu kỳ Trăng
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
class BaseMoonPhase extends BaseMjd implements MoonPhaseInterface
{
    /**
     * Chu kỳ số ngày Mặt trăng quay quanh Trái đất
     */
    public const SYN_MOON = 29.53058868;

    /**
     * Tạo đối tượng mới
     * 
     * @param float $jd Số ngày MJD của pha Mặt trăng
     * @param int $totalCysles Tổng số pha mặt trăng kể từ 1900-01-01T00:00+0000 cho đến Pha hiện tại 'hiện tại'
     * @param int $offset 
     * @return void 
     */
    public function __construct(float $jd, protected int $totalCysles, int $offset = self::VN_OFFSET)
    {
        parent::__construct($jd, $offset);
    }

    /**
     * Tính toán thời gian của điểm Sóc từ một mốc ngày MJD
     * 
     * @param int   $jdn
     * @param float $k Tham số này chỉ số tháng đồng bộ được tính toán trước, theo công thức
     *                 K = (năm dương lịch - 1990) * 12.3685
     * @return float
     */
    protected function meanphase(int $jdn, float $k): float
    {
        $jt = ($jdn - 2415020.0) / 36525;
        $t2 = $jt * $jt;
        $t3 = $t2 * $jt;

        $nt1 = 2415020.75933 + self::SYN_MOON * $k
            + 0.0001178 * $t2
            - 0.000000155 * $t3
            + 0.00033 * sin(deg2rad(166.56 + 132.87 * $jt - 0.009173 * $t2));

        return $nt1;
    }

    /**
     * Đưa ra giá trị K là tổng số chu kỳ Trăng đã qua kể từ 1900-01-01T00:00+0000 để xác định pha trung bình của trăng 
     * mới và bộ chọn pha (0,0, 0,25, 0,5, 0,75), thu được thời gian pha thực, đã hiệu chỉnh.
     *
     * @param float $k
     * @param float $phase  0.0     -   Trăng mới - Sóc (New moon)
     *                      0.25    -   Bán nguyệt đầu tháng - Thượng huyền (First quarter)
     *                      0.50    -   Trăng tròn - Rằm (Full moon)
     *                      0.75    -   Bán nguyệt cuối tháng - Hạ huyền (Last quarter)
     * @return float|null
     */
    protected function truephase(float $k, float $phase): ?float
    {
        $apcor = false;

        $k += $phase;
        $t = $k / 1236.85;
        $t2 = $t * $t;
        $t3 = $t2 * $t;
        $pt = 2415020.75933
            + self::SYN_MOON * $k
            + 0.0001178 * $t2
            - 0.000000155 * $t3
            + 0.00033 * sin(deg2rad(166.56 + 132.87 * $t - 0.009173 * $t2));

        $m = 359.2242 + 29.10535608 * $k - 0.0000333 * $t2 - 0.00000347 * $t3;
        $mprime = 306.0253 + 385.81691806 * $k + 0.0107306 * $t2 + 0.00001236 * $t3;
        $f = 21.2964 + 390.67050646 * $k - 0.0016528 * $t2 - 0.00000239 * $t3;

        if ($phase < 0.01 || abs($phase - 0.5) < 0.01) 
        {
            $pt += (0.1734 - 0.000393 * $t) * sin(deg2rad($m))
                + 0.0021 * sin(deg2rad(2 * $m))
                - 0.4068 * sin(deg2rad($mprime))
                + 0.0161 * sin(deg2rad(2 * $mprime))
                - 0.0004 * sin(deg2rad(3 * $mprime))
                + 0.0104 * sin(deg2rad(2 * $f))
                - 0.0051 * sin(deg2rad($m + $mprime))
                - 0.0074 * sin(deg2rad($m - $mprime))
                + 0.0004 * sin(deg2rad(2 * $f + $m))
                - 0.0004 * sin(deg2rad(2 * $f - $m))
                - 0.0006 * sin(deg2rad(2 * $f + $mprime))
                + 0.0010 * sin(deg2rad(2 * $f - $mprime))
                + 0.0005 * sin(deg2rad($m + 2 * $mprime));

            $apcor = true;
        } 
        elseif (abs($phase - 0.25) < 0.01 || abs($phase - 0.75) < 0.01) 
        {
            $pt += (0.1721 - 0.0004 * $t) * sin(deg2rad($m))
                + 0.0021 * sin(deg2rad(2 * $m))
                - 0.6280 * sin(deg2rad($mprime))
                + 0.0089 * sin(deg2rad(2 * $mprime))
                - 0.0004 * sin(deg2rad(3 * $mprime))
                + 0.0079 * sin(deg2rad(2 * $f))
                - 0.0119 * sin(deg2rad($m + $mprime))
                - 0.0047 * sin(deg2rad($m - $mprime))
                + 0.0003 * sin(deg2rad(2 * $f + $m))
                - 0.0004 * sin(deg2rad(2 * $f - $m))
                - 0.0006 * sin(deg2rad(2 * $f + $mprime))
                + 0.0021 * sin(deg2rad(2 * $f - $mprime))
                + 0.0003 * sin(deg2rad($m + 2 * $mprime))
                + 0.0004 * sin(deg2rad($m - 2 * $mprime))
                - 0.0003 * sin(deg2rad(2 * $m + $mprime));

            if ($phase < 0.5) {
                $pt += 0.0028 - 0.0004 * cos(deg2rad($m)) + 0.0003 * cos(deg2rad($mprime));
            } else {
                $pt += -0.0028 + 0.0004 * cos(deg2rad($m)) - 0.0003 * cos(deg2rad($mprime));
            }

            $apcor = true;
        }

        return $apcor ? $pt + 0.5 : null;
    }

    /**
     * {@inheritdoc}
     * 
     * @param int $phaseNumber 
     * @return MoonPhaseInterface 
     */
    public function add(int $phaseNumber): MoonPhaseInterface 
    { 
        $totalCysles = $this->getTotalCycles() + $phaseNumber;
        $mjd = $this->truephase($totalCysles, 0.0);

        return new BaseMoonPhase($mjd, $totalCysles, $this->getOffset());
    }

    /**
     * {@inheritdoc}
     * 
     * @param int $phaseNumber 
     * @return MoonPhaseInterface 
     */
    public function subtract(int $phaseNumber): MoonPhaseInterface 
    { 
        return $this->add($phaseNumber * -1);
    }

    /**
     * {@inheritdoc}
     * @return int 
     */
    public function getTotalCycles(): int
    { 
        return $this->totalCysles;
    }
}