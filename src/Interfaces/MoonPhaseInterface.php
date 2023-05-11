<?php namespace VanTran\LunarCalendar\Interfaces;

/**
 * Giao diện xác định các phương thức của một pha Mặt trăng riêng lẻ. Trong việc lập Âm lịch, chỉ sử dụng pha Trăng mới
 * (Sóc), do vậy không nhất thiết phải tính toán dữ liệu của các pha khác trong một chu kỳ Trăng.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
interface MoonPhaseInterface extends JulianDayNumberInterface
{
    /**
     * Số ngày Mặt trăng hoàn thành 1 vòng quay quanh Trái đất (1 chu kỳ)
     */
    public const SYN_MOON = 29.53058868;

    /**
     * Trả về tổng số chu kỳ Mặt trăng đã qua kể từ 1900-01-01T00:00+0000, mỗi chu kỳ được xác định là một vòng hoàn
     * thành quanh quỹ đạo của Trái đất.
     * @return int
     */
    public function getTotalCycles(): int;

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