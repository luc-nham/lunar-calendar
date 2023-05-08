<?php namespace VanTran\LunarCalendar\Converters;

use DateTimeInterface;

/**
 * Lớp chuyển đổi một đối tượng triển khai DateTimeInterface thành số ngày JDN
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class DateTimeToJdnConverter extends GregorianToJDNConverter
{
    public function __construct(DateTimeInterface $datetime)
    {
        parent::__construct(
            $datetime->format('Y'),
            $datetime->format('n'),
            $datetime->format('j'),
            $datetime->format('H'),
            $datetime->format('i'),
            $datetime->format('s'),
            $datetime->format('Z')
        );
    }
}