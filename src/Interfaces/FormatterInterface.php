<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện cho các lớp định dạng thời gian
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface FormatterInterface
{
    /**
     * Định dạng chuỗi thời gian
     * 
     * @param string $format 
     * @return string 
     */
    public function format(string $format): string;
}