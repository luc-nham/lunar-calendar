<?php namespace VanTran\LunarCalendar\Converters;

use VanTran\LunarCalendar\Interfaces\SunlongitudeMutableInterface;
use VanTran\LunarCalendar\Traits\SunlongitudeMutable;

/**
 * Bộ chuyển đổi một mốc ngày Julian thành góc Kinh độ Mặt trời, có khả năng biến đổi, tìm kiếm các vị trí Kinh độ mới 
 * từ điểm hiện tại.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToSunlongitudeMutableConverter extends JdnToSunlongitudeConverter implements SunlongitudeMutableInterface
{
    use SunlongitudeMutable;
}