<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

/**
 * Bộ chuyển đổi một mốc ngày Julian thành pha Trăng mới
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToNewMoonPhaseConverter extends BaseNewMoonPhaseConverter
{
    public function __construct(JulianDayNumberInterface $jdn, int $mode = self::NORMAL_MODE)
    {
        parent::__construct($jdn->getJd(), $jdn->getOffset(), $mode);
    }
}