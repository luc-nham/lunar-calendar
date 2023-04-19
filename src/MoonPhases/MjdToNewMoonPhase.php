<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Lớp tìm điểm Trăng mới tương ứng của một mốc ngày MJD
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
class MjdToNewMoonPhase extends BaseMoonPhase implements NewMoonPhaseInterface
{
    /**
     * Ở chế độ nghiêm ngặt, trong trường hợp số ngày MJD đầu vào (A) chính là ngày tương ứng với pha Trăng mới (B), 
     * nhưng nếu (A) nhỏ hơn (B) ở phần số thập phân xác định giờ phút giây, thì (A) sẽ được xác định thuộc về pha Trăng
     * mới trước đó.
     */
    public const STRICT_MODE = 2;

    /**
     * Ở chế độ thông thường, trong trường hợp số ngày MJD đầu vào (A) chính là ngày tương ứng với pha Trăng mới (B), 
     * nhưng nếu (A) nhỏ hơn (B)  ở phần số thập phân xác định giờ phút giây, thì (A) vẫn sẽ được xác định thuộc về pha
     * Trăng mới hiện tại.
     */
    public const NORMAL_MODE = 1;

    /**
     * Tạo đối tượng mới
     * 
     * @param MjdInterface $mjd Số ngày MJD của thời điểm cần tính toán pha Trăng mới tương ứng với nó
     * @return void 
     */
    public function __construct(MjdInterface $mjd, protected int $mode = self::NORMAL_MODE)
    {
        $this->init($mjd);
    }

    /**
     * Tính toán và khởi tạo các thuộc tính của pha Trăng mới (Sóc) từ thời điểm đầu vào
     * 
     * @param MjdInterface $mjd 
     * @return void 
     */
    final protected function init(MjdInterface $mjd): void
    {
        $sdate = $mjd->getJd();

        if ($this->mode === self::NORMAL_MODE) {
            $sdate = floor($mjd->getJd()) + 0.9999884259;
        }

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

        $this->totalCysles = $k1;
        $this->jd = $this->truephase($k1, 0.0);
        $this->offset = $mjd->getOffset();
    } 
}