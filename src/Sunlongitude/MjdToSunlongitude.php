<?php namespace VanTran\LunarCalendar\Sunlongitude;

use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Bộ chuyển đổi mốc ngày MJD thành góc Kinh độ Mặt trời
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Sunlongitude
 */
class MjdToSunlongitude extends BaseSunlongitude
{
    /**
     * Tạo đối tượng mới
     * 
     * @param MjdInterface $mjd 
     * @return void 
     */
    public function __construct(MjdInterface $mjd)
    {
        parent::__construct(
            $mjd->getJd(),
            $mjd->getOffset()
        );
    }
}