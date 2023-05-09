<?php namespace VanTran\LunarCalendar\Storages;

use Exception;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeStorageMutableInterface;

/**
 * Lớp lưu trữ các thuộc tính Âm lịch có thể điều chỉnh được, nhằm mục đích cung cấp các giá trị đầu vào hoặc đầu ra để
 * cung cấp cho các bộ chuyển đổi Âm - Dương lịch.
 * 
 * @author Văn Trần <caovan.info@gmail.com>
 * @package VanTran\LunarCalendar\Storages
 */
class LunarDateTimeStorageMutable extends DateTimeStorageMutable implements LunarDateTimeStorageMutableInterface
{
    /**
     * @var bool Xác định tháng Âm lịch nhuận hay không
     */
    protected bool $leap = false;

    /**
     * @inheritdoc
     */
    public function setIsLeapMonth(bool $isLeap): void 
    { 
        $this->leap = $isLeap;
    }

    /**
     * @inheritdoc
     */
    public function isLeapMonth(): bool 
    { 
        return $this->leap;
    }
}