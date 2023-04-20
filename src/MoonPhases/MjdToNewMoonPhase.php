<?php namespace VanTran\LunarCalendar\MoonPhases;

use VanTran\LunarCalendar\Mjd\MjdInterface;

/**
 * Lớp tìm điểm Trăng mới tương ứng của một mốc ngày MJD
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\MoonPhases
 */
class MjdToNewMoonPhase extends BaseNewMoonPhase
{
    /**
     * Tạo đối tượng mới
     * 
     * @param MjdInterface $mjd Số ngày MJD của thời điểm cần tính toán pha Trăng mới tương ứng với nó
     * @return void 
     */
    public function __construct(MjdInterface $mjd, int $mode = self::NORMAL_MODE)
    {
        parent::__construct($mjd->getJd(), $mjd->getOffset(), $mode);
    }
}