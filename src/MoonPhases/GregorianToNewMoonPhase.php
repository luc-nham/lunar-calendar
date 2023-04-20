<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Mjd\GregorianToMjd;

/**
 * Bộ chuyển đổi mốc ngày lịch Gregorian thành pha Trăng mới
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
class GregorianToNewMoonPhase extends MjdToNewMoonPhase
{
    /**
     * Tạo đối tượng mới
     * 
     * @param int $offset Phần bù UTC
     * @param int $year Năm gồm 4 chữ số
     * @param int $month Tháng từ 1 đến 12
     * @param int $day Ngày từ 1 đến 31
     * @param int $hour Giờ từ 1 đến 23
     * @param int $minute Phút từ 0 đến 59
     * @param int $second Giây từ 0 đến 59
     * @return void 
     */
    public function __construct(
        protected int $offset, 
        protected int $year, 
        protected int $month, 
        protected int $day, 
        protected int $hour = 0, 
        protected int $minute = 0,
        protected int $second = 0
    ) {
        parent::__construct(
            new GregorianToMjd($offset, $year, $month, $day, $hour, $minute, $second)
        );
    }
}