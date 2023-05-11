<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện cho các lớp quản lý, chuyển đổi và truy xuất giá trị của hệ thống Can, Chi.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface SexagenariesHandlerInterface
{
    /**
     * Trả về số chỉ mục của hệ thống Can Chi.
     *  
     * @param string $char 
     * @return null|int 
     */
    public function getIndex(string $key): string|int;

    /**
     * Trả về ký tự đại diện của Can hoặc Chi
     * 
     * @param string $key 
     * @return string 
     */
    public function getCharacter(string $key): string;
}