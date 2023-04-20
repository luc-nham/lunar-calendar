<?php namespace VanTran\LunarCalendar\MoonPhases;

class BaseNewMoonPhase extends BaseMoonPhase implements NewMoonPhaseInterface
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

    public function __construct(float $jd, int $offset = self::VN_OFFSET, protected int $mode = self::NORMAL_MODE)
    {
        $cysles = $this->getTotalCyclesFromMjd($jd);
        $newMoonJd = $this->truephase($cysles, $this->getPhaseSelector());

        parent::__construct($newMoonJd, $cysles, $offset);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTotalCyclesFromMjd(float $mjd): int
    {
        if ($this->mode === self::NORMAL_MODE) {
            $mjd = floor($mjd) + 0.9999884259;
        }

        return parent::getTotalCyclesFromMjd($mjd);
    }

    /**
     * {@inheritdoc}
     */
    protected function getPhaseSelector(): float 
    { 
        return 0.0;
    }
    
}