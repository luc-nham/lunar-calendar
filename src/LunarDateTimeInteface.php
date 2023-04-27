<?php namespace VanTran\LunarCalendar;

use DateTimeZone;
use VanTran\LunarCalendar\Mjd\MjdInterface;

interface LunarDateTimeInteface extends MjdInterface
{
    /**
     * Múi giờ cho Âm lịch Việt Nam
     */
    public const VN_TIMEZONE = '+07:00';

    /**
     * Định dạng thời gian Âm lịch
     * 
     * @param string $format 
     * @return string 
     */
    public function format(string $format): string;

    /**
     * Trả về múi giờ địa phương
     * @return DateTimeZone 
     */
    public function getTimezone(): DateTimeZone;

    /**
     * Trả về Độ lệch múi giờ tính bằng giây
     * @return int 
     */
    public function getOffset(): int;

    /**
     * Trả về thời gian Unix
     * @return int 
     */
    public function getTimestamp(): int;
}