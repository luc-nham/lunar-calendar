<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Bộ chuyển đổi một mốc ngày Julian thành góc Kinh độ Mặt trời
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToSunlongitudeConverter extends BaseSunlongitudeConverter
{
    /**
     * Tạo đối tượng mới
     * 
     * @param JulianDayNumberInterface $jdn
     * @return void 
     */
    public function __construct(JulianDayNumberInterface $jdn)
    {
        parent::__construct($jdn->getJd(), $jdn->getOffset());
    }
}