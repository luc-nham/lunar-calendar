<?php namespace VanTran\LunarCalendar\Lunar;

use DateTimeZone;
use Exception;

/**
 * Lớp cấu hình tiêu chuẩn cho các dữ liệu Âm lịch đầu vào, nó cũng có thể được sử dụng để lưu trữ các mốc thời gian
 * dương lịch. Mục tiêu để cung cấp cho các thành phần tính toán Âm lịch.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Lunar
 */
class LunarDateTimeInput implements LunarInputInterface
{
    /**
     * @var int Độ lệch múi giờ địa phương, tính bằng giây
     */
    private int $offset = self::VN_OFFSET;

    /**
     * @var int Năm địng dạng gồm 4 chữ số, hỗ trợ tối thiểu 1901, tối đa 2100
     */
    private int $year = self::MIN_YEAR;

    /**
     * @var int Tháng từ 1 - 12
     */
    private int $month = 1;

    /**
     * @var int Ngày trong tháng, Dương lịch từ 28 - 31, âm lịch từ 29 -30
     */
    private int $day = 1;

    /**
     * @var int Giờ từ 0 - 23
     */
    private int $hour = 0;

    /**
     * @var int Phút từ 0 - 59
     */
    private int $minute = 0;

    /**
     * @var int Giây từ 0 - 59
     */
    private int $second = 0;

    /**
     * 
     * @var bool Xác định có phải tháng nhuận
     */
    private bool $leap = false;

    /**
     * 
     * @var DateTimeZone Múi giờ địa phương
     */
    private $timezone;

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
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function isLeapMonth(): bool 
    { 
        return $this->leap;
    }

    /**
     * {@inheritdoc}
     */
    public function getTimezone(): ?DateTimeZone 
    { 
        return $this->timezone;
    }

    /**
     * Đặt giá trị mới cho năm
     * 
     * @param int $year 
     * @return LunarDateTimeInput 
     */
    public function setYear(int $year): self
    {
        if ($year < self::MIN_YEAR || $year > self::MAX_YEAR) {
            throw new Exception("Error. The year is out of supported.");
        }

        $this->year = $year;
        return $this;
    }

    /**
     * Đặt giá trị tháng
     * 
     * @param int $month 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setMonth(int $month): self
    {
        if ($month < 1 || $month > 12) {
            throw new Exception("Error. The month must be from 1 to 12.");
        }

        $this->month = $month;
        return $this;
    }

    /**
     * Đặt giá trị ngày
     * 
     * @param int $day 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setDay(int $day): self
    {
        if ($day < 1 || $day > 31) {
            throw new Exception("Error. The day must be from 1 to 31.");
        }

        $this->day = $day;
        return $this;
    }

    /**
     * Đặt giá trị giờ
     * 
     * @param int $hour 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setHour(int $hour): self
    {
        if ($hour < 0 || $hour > 23) {
            throw new Exception("Error. The hour must be from 0 to 23.");
        }

        $this->hour = $hour;
        return $this;
    }

    /**
     * Đặt giá trị phút
     * 
     * @param int $minute 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function seMinute(int $minute): self
    {
        if ($minute < 0 || $minute > 59) {
            throw new Exception("Error. The minute must be from 0 to 59.");
        }

        $this->minute = $minute;
        return $this;
    }

    /**
     * Đặt giá trị giây
     * 
     * @param int $second 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setSecond(int $second): self
    {
        if ($second < 0 || $second > 59) {
            throw new Exception("Error. The second must be from 0 to 59.");
        }

        $this->second = $second;
        return $this;
    }

    /**
     * Đặt độ lệch múi giờ địa phương
     * 
     * @param int $offset 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setOffset(int $offset): self
    {
        if ($offset < -43200 || $offset > 50400) {
            throw new Exception("Error. Invalid offset value.");
        }

        $this->offset = $offset;
        return $this;
    }

    /**
     * Đặt giá trị xác định tháng có nhuận hay không
     * 
     * @param bool $leap 
     * @return LunarDateTimeInput 
     */
    public function setLeap(bool $leap): self
    {
        $this->leap = $leap;
        return $this;
    }

    /**
     * Đặt múi giờ địa phương
     * 
     * @param DateTimeZone $timezone 
     * @return LunarDateTimeInput 
     */
    public function setTimeZone(DateTimeZone $timezone): self
    {
        $this->timezone = $timezone;
        return $this;
    }
}