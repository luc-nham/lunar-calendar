<?php namespace VanTran\LunarCalendar\Storages;

use DateTimeInterface;
use Exception;

/**
 * Tạo nhanh một vùng chứa thời gian âm lịch có thể biến đổi từ một đối tượng triển khai DateTimeInterface
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Storages
 */
class GregorianToLunarStorageMutable extends LunarDateTimeStorageMutable
{
    /**
     * Tạo đối tượng mới
     * 
     * @param DateTimeInterface $datetime 
     * @return void 
     * @throws Exception 
     */
    public function __construct(DateTimeInterface $datetime)
    {
        $this->setYear($datetime->format('Y'));
        $this->setMonth($datetime->format('n'));
        $this->setDay($datetime->format('j'));
        $this->setHour($datetime->format('H'));
        $this->setMinute($datetime->format('i'));
        $this->setSecond($datetime->format('s'));
        $this->setOffset($datetime->getOffset());
        $this->setTimeZone($datetime->getTimezone());
    }
}