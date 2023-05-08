<?php namespace VanTran\LunarCalendar\Converters;

use DateTimeZone;
use Exception;
use VanTran\LunarCalendar\Storages\DateTimeStorageMutable;

/**
 * Bộ chuyển đổi số ngày Julian (JDN) thành lịch Gregory. Lưu ý rằng đầu ra có thể bị chênh lệch lớn hoặc nhỏ hơn 1 giây
 * so với điểm thực tế, do đó không nên sử dụng để định dạng chính xác giờ, phút, giây.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Converters
 */
class BaseJdnToGregorian extends DateTimeStorageMutable
{
    /**
     * Tạo mới đối tượng
     * 
     * @param float $jdn Số ngày Julian của thời điểm cần chuyển đổi. Lưu ý rằng JDN giống như Timestamp, tức tính theo 
     *                   giờ UTC, thì đầu ra cũng luôn là ngày tháng tương ứng với UTC.
     * @return void 
     */
    public function __construct(private int|float $jdn)
    {
        $this->initDate();
        $this->initTime();
    }

    /**
     * Khởi tạo ngày tháng
     * 
     * @return void 
     * @throws Exception 
     */
    final protected function initDate(): void
    {
        $j = floor($this->jdn);
        
        if ($this->jdn - $j >= 0.5) {
            $j += 1;
        }

        $j = $j - 1721119;
        $y = floor((4 * $j - 1) / 146097);
        $j = 4 * $j - 1 - 146097 * $y;
        $d = floor($j / 4);
        $j = floor((4 * $d + 3) / 1461);
        $d = 4 * $d + 3 - 1461 * $j;
        $d = floor(($d + 4) / 4);
        $m = floor((5 * $d - 3) / 153);
        $d = 5 * $d - 3 - 153 * $m;
        $d = floor(($d + 5) / 5);
        $y = 100 * $y + $j;

        if ($m < 10) {
            $m += 3;
        } else {
            $m -= 9;
            $y += 1;
        }

        $this->setYear($y);
        $this->setMonth($m);
        $this->setDay($d);
    }

    /**
     * Khởi tạo giờ phút giây
     * 
     * @return void 
     * @throws Exception 
     */
    final protected function initTime(): void
    {
        $fragtion = $this->jdn - floor($this->jdn);

        if ($fragtion === 0.5) {
            return;
        }

        if ($fragtion === 0) {
            $this->setHour(12);
            return;
        }

        $seconds = floor($fragtion * 86400) + 43200;

        $h = (floor($seconds / 3600)) % 24;
        $i = (floor($seconds / 60)) % 60;
        $s = $seconds % 60;

        $this->setHour($h);
        $this->setMinute($i);
        $this->setSecond($s);
    }

    /**
     * @inheritdoc
     */
    public function getOffset(): int
    {
        return 0;
    }

    /**
     * @inheritdoc
     */
    public function getTimezone(): ?DateTimeZone
    {
        if (!$this->timezone) {
            $this->timezone = new DateTimeZone('UTC');
        }

        return $this->timezone;
    }
}