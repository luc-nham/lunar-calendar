<?php namespace VanTran\LunarCalendar;

use VanTran\LunarCalendar\Converters\LunarToSexagenaryConverter;
use VanTran\LunarCalendar\Formatters\SexagenaryFormatter;
use VanTran\LunarCalendar\Interfaces\FormatterInterface;
use VanTran\LunarCalendar\Interfaces\LunarDateTimeInteface;
use VanTran\LunarCalendar\Interfaces\SexagenariesHandlerInterface;

class LunarSexagenary implements FormatterInterface
{
    /**
     * @var SexagenariesHandlerInterface
     */
    private $handler;
    private $formatter;

    public function __construct(private LunarDateTimeInteface $lunar)
    {
        
    }

    /**
     * Trình xử lý hệ thống Can Chi mặc định
     * 
     * @return LunarToSexagenaryConverter 
     */
    private function getDefaultHandler(): LunarToSexagenaryConverter
    {
        return new LunarToSexagenaryConverter($this->lunar);
    }

    /**
     * Trả về bộ định dạng Can Chi mặc định
     * @return SexagenaryFormatter 
     */
    private function getDefaultFormatter(): SexagenaryFormatter
    {
        return new SexagenaryFormatter($this->getHandler());
    }

    /**
     * Trả về bộ định dạng hệ thống Can Chi
     * @return FormatterInterface 
     */
    protected function getFormatter(): FormatterInterface
    {
        if (!$this->formatter) {
            $this->formatter = $this->getDefaultFormatter();
        }

        return $this->formatter;
    }

    /**
     * Trả về bộ xử lý hệ thống Can Chi
     * @return SexagenariesHandlerInterface 
     */
    protected function getHandler(): SexagenariesHandlerInterface
    {
        if (!$this->handler) {
            $this->handler = $this->getDefaultHandler();
        }

        return $this->handler;
    }

    /**
     * Định dạng hệ thống Can Chi, chuỗi định dạng hỗ trợ các mẫu:
     * - D | %D: Can của ngày
     * - d | %d: Chi của ngày
     * - M | %M: Can của tháng
     * - m | %m: Chi của tháng
     * - Y | %Y: Can của năm
     * - y | %y: Chi của năm
     * - H | %H: Can của giờ hiện tại
     * - h | %h: Chi của giờ hiện tại
     * - N | %N: Can của giờ Tý (giờ bắt đầu ngày mới trong Âm lịch)
     * - D+: tương đương %D %d
     * - M+: tương đương %M %m
     * - Y+: tương đương %Y %y
     * - H+: tương đương %H %h
     * - 
     */
    public function format(string $format): string
    {
        return $this->getFormatter()->format($format);
    }
}