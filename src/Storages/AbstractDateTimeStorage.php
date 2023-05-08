<?php namespace VanTran\LunarCalendar\Storages;

use DateTime;
use DateTimeZone;
use VanTran\LunarCalendar\Interfaces\DateTimeStorageInterface;

/**
 * Lớp trừu tượng lưu trữ và truy xuất các giá trị thời gian của 1 thời điểm, nhằm mục đích cung cấp đầu vào hoặc đầu ra 
 * cho các bộ chuyển đổi Âm - Dương lịch.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Storages
 */
class AbstractDateTimeStorage implements DateTimeStorageInterface
{
    /**
     * @var int Độ lệch múi giờ địa phương, tính bằng giây
     */
    protected int $offset = 0;

    /**
     * @var int Năm địng dạng gồm 4 chữ số, hỗ trợ tối thiểu 1901, tối đa 2100
     */
    protected int $year = self::MIN_YEAR;

    /**
     * @var int Tháng từ 1 - 12
     */
    protected int $month = 1;

    /**
     * @var int Ngày trong tháng, Dương lịch từ 28 - 31, âm lịch từ 29 -30
     */
    protected int $day = 1;

    /**
     * @var int Giờ từ 0 - 23
     */
    protected int $hour = 0;

    /**
     * @var int Phút từ 0 - 59
     */
    protected int $minute = 0;

    /**
     * @var int Giây từ 0 - 59
     */
    protected int $second = 0;

    /**
     * @var null|DateTimeZone Múi giờ địa phương
     */
    protected $timezone;

    /**
     * {@inheritdoc}
     */
    public function getYear(): int 
    { 
        return $this->year;
    }

    /**
     * {@inheritdoc}
     */
    public function getMonth(): int 
    { 
        return $this->month;
    }

    /**
     * {@inheritdoc}
     */
    public function getDay(): int 
    { 
        return $this->day;
    }

    /**
     * {@inheritdoc}
     */
    public function getHour(): int 
    { 
        return $this->hour;
    }

    /**
     * {@inheritdoc}
     */
    public function getMinute(): int 
    { 
        return $this->minute;
    }

    /**
     * {@inheritdoc}
     */
    public function getSecond(): int 
    { 
        return $this->second;
    }

    /**
     * {@inheritdoc}
     */
    public function getOffset(): int 
    { 
        if (null === $this->offset) {
            if (!$this->getTimezone()) {
                return 0;
            }

            $dateStr = sprintf(
                "%d-%d-%d %d:%d:%d",
                $this->getYear(),
                $this->getMonth(),
                $this->getDay(),
                $this->getHour(),
                $this->getMinute(),
                $this->getSecond()
            );
            $date = new DateTime($dateStr, $this->getTimezone());
            $this->offset = $date->getOffset();
        }

        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone(): ?DateTimeZone 
    { 
        return $this->timezone;
    }
}