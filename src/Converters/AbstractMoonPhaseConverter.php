<?php namespace VanTran\LunarCalendar\Converters;

use Exception;
use VanTran\LunarCalendar\Interfaces\MoonPhaseInterface;

abstract class AbstractMoonPhaseConverter extends BaseJDN implements MoonPhaseInterface
{
    /**
     * Đăng ký một trong các giá trị của bộ chọn pha bao gồm:
     * - 0.0 :   Trăng mới - Sóc (New moon)
     * - 0.25:   Bán nguyệt đầu tháng - Thượng huyền (First quarter)
     * - 0.50:   Trăng tròn - Rằm (Full moon)
     * - 0.75:   Bán nguyệt cuối tháng - Hạ huyền (Last quarter)
     * @return float 
     */
    abstract protected function registerPhaseSelector(): float;

    /**
     * @var int Tổng số chu kỳ trăng đã qua kể từ 1900-01-01T00:00+0000 cho đến điểm đang tính
     */
    private $totalCysles;

    public function __construct(float $jd, int $offset)
    {
        $cysles = $this->getTotalCyclesFromJdn($jd);
        $this->setTotalCycles($cysles);
        
        parent::__construct(
            $this->truephase($cysles, $this->getPhaseSelector()),
            $offset
        );
    }

    /**
     * Trả về bộ chọn pha
     * 
     * @return float 
     * @throws Exception 
     */
    private function getPhaseSelector(): float
    {
        $selector = $this->registerPhaseSelector();

        if (!in_array($selector, [0.0, 0.25, 0.50, 0.75])) {
            throw new Exception("Invalid phase selector.");
        }

        return $selector;
    }

    /**
     * Tính toán và trả về tổng số chu kỳ mặt trăng kể từ 1900-01-01T00:00+0000
     * 
     * @param float $jdn Mốc ngày MJD của thời điểm cần tính
     * @return int 
     */
    protected function getTotalCyclesFromJdn(float $jdn): int
    {
        $sdate = $jdn;
        $adate = $sdate - 45;
        $dates = explode('/', jdtogregorian($adate));
        $yy = $dates[2];
        $mm = $dates[0];

        $k1 = floor(($yy + (($mm - 1) * (1 / 12)) - 1900) * 12.3685);
        $adate = $nt1 = $this->meanphase((int) $adate, $k1);

        while (true) {
            $adate += self::SYN_MOON;
            $k2 = $k1 + 1;
            $nt2 = $this->meanphase((int) $adate, $k2);

            if (abs($nt2 - $sdate) < 0.75) {
                $nt2 = $this->truephase($k2, 0.0);
            }

            if ($nt1 <= $sdate && $nt2 > $sdate) {
                break;
            }

            $nt1 = $nt2;
            $k1 = $k2;
        }

        return $k1;
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

        return $apcor ? $pt : null;
    }

    /**
     * Thay đổi giá trị tổng chu kỳ trăng kể từ 1900-01-01T00:00+0000
     * @param int $cycles 
     * @return void 
     */
    public function setTotalCycles(int $cycles): void
    {
        $this->totalCysles = $cycles;
    }

    public function getTotalCycles(): int 
    { 
        return $this->totalCysles;
    }

    # @inheritdoc
    public function add(int $phaseNumber): MoonPhaseInterface 
    { 
        $selector = $this->getPhaseSelector();

        $totalCysles = $this->getTotalCycles() + $phaseNumber;
        $jdn = $this->truephase($totalCysles, $selector);

        $newPhase = clone($this);
        $newPhase->setJd($jdn);
        $newPhase->setTotalCycles($totalCysles);

        return $newPhase;
    }
   
    # @inheritdoc
    public function subtract(int $phaseNumber): MoonPhaseInterface 
    { 
        return $this->add($phaseNumber * -1);
    }
}
