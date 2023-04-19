<?php namespace VanTran\LunarCalendar\Mjd;

/**
 * Lớp tính toán mốc ngày MJD từ một nhóm thời gian dương lịch
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Mjd
 */
class GregorianToMjd extends BaseMjd
{
    /**
     * Tạo đối tượng mới
     * 
     * @param int $offset Phần bù UTC
     * @param int $year Năm gồm 4 chữ số
     * @param int $month Tháng từ 1 đến 12
     * @param int $day Ngày từ 1 đến 31
     * @param int $hour Giờ từ 1 đến 23
     * @param int $minute Phút từ 0 đến 59
     * @param int $second Giây từ 0 đến 59
     * @return void 
     */
    public function __construct(
        protected int $offset, 
        protected int $year, 
        protected int $month, 
        protected int $day, 
        protected int $hour = 0, 
        protected int $minute = 0,
        protected int $second = 0
    )
    {
        $jdn = gregoriantojd($this->month, $this->day, $this->year);
        $jdn += ($this->hour * 3600 + $this->minute * 60 + $this->second) / 86400;

        if ($this->offset !== 0) {
            $jdn -= $this->offset / 86400;
        }

        parent::__construct($jdn, $offset);
    }
}