<?php namespace VanTran\LunarCalendar\Storages;

use DateTimeZone;
use Exception;
use VanTran\LunarCalendar\Interfaces\DateTimeStorageMutableInterface;

/**
 * Lớp lưu trữ các dữ liệu thời gian Dương lịch, có thể điều chỉnh, nhằm mục đích cung cấp các thông tin đầu vào hoặc
 * đầu ra cho các bộ chuyển đổi Âm - Dương lịch.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Storages
 */
class DateTimeStorageMutable extends AbstractDateTimeStorage implements DateTimeStorageMutableInterface
{
    /**
     * @inheritdoc
     */
    public function setYear(int $year): void
    {
        if ($year < self::MIN_YEAR || $year > self::MAX_YEAR) {
            throw new Exception("Error. The year is out of supported.");
        }

        $this->year = $year;
    }

    /**
     * @inheritdoc
     */
    public function setMonth(int $month): void
    {
        if ($month < 1 || $month > 12) {
            throw new Exception("Error. The month must be from 1 to 12.");
        }

        $this->month = $month;
    }

    /**
     * @inheritdoc
     */
    public function setDay(int $day): void
    {
        if ($day < 1 || $day > 31) {
            throw new Exception("Error. The day must be from 1 to 31.");
        }

        $this->day = $day;
    }

    /**
     * Đặt giá trị giờ
     * 
     * @param int $hour 
     * @return LunarDateTimeInput 
     * @throws Exception 
     */
    public function setHour(int $hour): void
    {
        if ($hour < 0 || $hour > 23) {
            throw new Exception("Error. The hour must be from 0 to 23.");
        }

        $this->hour = $hour;
    }

    /**
     * @inheritdoc
     */
    public function setMinute(int $minute): void
    {
        if ($minute < 0 || $minute > 59) {
            throw new Exception("Error. The minute must be from 0 to 59.");
        }

        $this->minute = $minute;
    }

    /**
     * @inheritdoc
     */
    public function setSecond(int $second): void
    {
        if ($second < 0 || $second > 59) {
            throw new Exception("Error. The second must be from 0 to 59.");
        }

        $this->second = $second;
    }

    /**
     * @inheritdoc
     */
    public function setOffset(int $offset): void
    {
        if ($offset < -43200 || $offset > 50400) {
            throw new Exception("Error. Invalid offset value.");
        }

        $this->offset = $offset;
    }

    /**
     * @inheritdoc
     */
    public function setTimeZone(DateTimeZone $timezone): void
    {
        $this->timezone = $timezone;
    }
    
}