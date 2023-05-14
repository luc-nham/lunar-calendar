<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Bộ chuyển đổi một mốc ngày Julian thành Tiết khí tương ứng
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToSolarTermConverter extends BaseSolarTermConverter
{
    /**
     * Tạo đối tượng mới
     * 
     * @param JulianDayNumberInterface $jd Mốc ngày Julian
     * @return void 
     */
    public function __construct(JulianDayNumberInterface $jd)
    {
        parent::__construct($jd->getJd(), $jd->getOffset());
    }
}