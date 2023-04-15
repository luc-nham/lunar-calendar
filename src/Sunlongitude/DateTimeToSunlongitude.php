<?php namespace VanTran\LunarCalendar\Sunlongitude;

use DateTime;
use DateTimeInterface;
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;

/**
 * Lớp tính toán Kinh độ Mặt trời từ một đối tượng triển khai DateTimeInterface
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Sunlongitude
 */
class DateTimeToSunlongitude extends MjdToSunlongitude
{
    public function __construct(?DateTimeInterface $dateTime = null)
    {
        if (!$dateTime) {
            $dateTime = new DateTime();
        }

        parent::__construct(new DateTimeToMjd($dateTime));
    }
}