<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định một đối tượng trong nhóm Can Chi, và cũng được dùng cho nhóm Tiết khí.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Interfaces
 */
interface TermInterface
{
    /**
     * Trả về số chỉ mục xác định đối tượng
     * 
     * @return int 
     */
    public function getIndex(): int;

    /**
     * Trả về chuỗi định danh đại diện cho đối tượng
     * 
     * @return string 
     */
    public function getKey(): string;

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

    /**
     * Trả về tên hiển thị cho đối tượng
     * 
     * @return string 
     */
    public function getLabel(): string;
}