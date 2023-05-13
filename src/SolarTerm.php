<?php namespace VanTran\LunarCalendar;

use DateTime;
use DateTimeZone;
use VanTran\LunarCalendar\Converters\DateTimeToJdnConverter;
use VanTran\LunarCalendar\Converters\JdnToSolarTermConverter;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;
use VanTran\LunarCalendar\Interfaces\SolarTermInterface;

/**
 * Lớp khởi tạo thệ thống Tiết Khí 
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar
 */
class SolarTerm extends JdnToSolarTermConverter implements SolarTermInterface
{   
    /**
     * Tạo đối tượng mới
     * 
     * @param null|JulianDayNumberInterface $jd Nếu mốc JDN không được cung cấp, tự động áp dụng thời điểm hiện tại
     * @return void 
     */
    public function __construct(?JulianDayNumberInterface $jd = null)
    {
        if (null === $jd) {
            $datetime = new DateTime();
            $jd = new DateTimeToJdnConverter($datetime);
        }

        parent::__construct($jd);
    }

    /**
     * Tạo nhanh Tiết khí từ thời điểm hiện tại
     * 
     * @param null|DateTimeZone $timezone 
     * @return SolarTerm 
     */
    public static function now(?DateTimeZone $timezone = null): SolarTerm
    {
        return self::createFromGregorian('now', $timezone);
    }

    /**
     * Khởi tạo tiết khí từ một mốc thời gian Dương lịch
     * 
     * @param string $datetime Chuỗi ngày tháng Gregorian hợp lệ
     * @param null|DateTimeZone $timezone 
     * @return SolarTerm 
     */
    public static function createFromGregorian(string $datetime, ?DateTimeZone $timezone = null): SolarTerm
    {
        $datetime = new DateTime($datetime, $timezone);
        $jd = new DateTimeToJdnConverter($datetime);

        return new self($jd);
    }
}