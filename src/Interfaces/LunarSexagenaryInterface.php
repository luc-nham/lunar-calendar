<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một đối tượng triển khai cần có khả năng trả về dữ liệu của một đối tượng trong nhóm Can Chi
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface LunarSexagenaryInterface extends FormatterInterface
{
    /**
     * Trả về 1 đối tượng Can hoặc Chi
     * 
     * @param string $key 
     * @return TermInterface 
     */
    public function getTerm(string $key): TermInterface;
}