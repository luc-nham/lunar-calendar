<?php namespace VanTran\LunarCalendar\Converters;

use DateTime;

/**
 * Bộ chuyển đổi một mốc ngày Julian thành đối tượng PHP DateTime giúp thuận tiện sử dụng lịch Gregorian.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class JdnToDateTimeConverter extends JdnToLocalGregorian
{
    /**
     * Trả về đối tượng DateTime
     * 
     * @return DateTime 
     */
    public function getDateTime(): DateTime
    {
        $date = new DateTime('', $this->getTimezone());
        
        $date->setDate($this->getYear(), $this->getMonth(), $this->getDay());
        $date->setTime($this->getHour(), $this->getMinute(), $this->getSecond());

        return $date;
    }
}