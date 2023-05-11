<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một đối tượng trong nhóm Can Chi
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface SexagenaryTermInterface
{
    /**
     * Trả về số chỉ mục xác định đối tượng
     * 
     * @return int 
     */
    public function getIndex(): int;

    /**
     * Trả về ký tự đại diện xác định đối tượng
     * 
     * @return string 
     */
    public function getCharacter(): string;

    /**
     * Trả về số hoặc chuỗi xác định loại đối tượng
     * 
     * @return int|string 
     */
    public function getType(): int|string;
}