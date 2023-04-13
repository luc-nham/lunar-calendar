<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Giao diện xác định các phương thức của một pha Mặt trăng
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
interface MoonPhaseInterface extends MjdInterface
{
    /**
     * Trả về tổng số lượng pha Mặt trăng đã qua kể từ 1900-01-01T00:00+0000
     * @return int
     */
    public function getTotalPhases(): int;

    /**
     * Trả về pha tương ứng chưa đến từ số lượng pha bổ sung (Tìm pha trong tương lai so với điểm đầu vào)
     * 
     * @param int $phaseNumber 
     * @return MoonPhaseInterface 
     */
    public function add(int $phaseNumber): MoonPhaseInterface;

    /**
     * Trả về pha đã qua từ số lượng pha bổ sung (Tìm pha ở quá khứ so với điểm đầu vào)
     * 
     * @param int $phaseNumber 
     * @return MoonPhaseInterface 
     */
    public function subtract(int $phaseNumber): MoonPhaseInterface;
}