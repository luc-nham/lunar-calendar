<?php namespace VanTran\LunarCalendar\Mjd;

use DateTime;
use DateTimeInterface;
use VanTran\LunarCalendar\Mjd\UnixToMjd;

/**
 * Bộ chuyển đổi một đối tượng triển khai DateTimeInterface thành mốc ngày MJD
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Mjd
 */
class DateTimeToMjd extends UnixToMjd
{
    /**
     * Tạo đối tượng mới
     * 
     * @param ?DateTimeInterface $datime
     * @return void
     */
    public function __construct(?DateTimeInterface $datime = null)
    {
        if (null == $datime) {
            $datime = new DateTime('now');
        }
        
        parent::__construct(
            $datime->getTimestamp(),
            $datime->getOffset()
        );
    }
}