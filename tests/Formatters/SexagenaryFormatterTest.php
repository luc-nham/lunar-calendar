<?php namespace VanTran\LunarCalendar\Tests\Formatters;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\LunarToSexagenaryConverter;
use VanTran\LunarCalendar\Formatters\SexagenaryFormatter;
use VanTran\LunarCalendar\LunarDateTime;

class SexagenaryFormatterTest extends TestCase
{
    private $timezone;

    public function setup(): void
    {
        $this->timezone = new DateTimeZone('+0700');
    }

    public function testOuput(): void
    {
        $lunar = new LunarDateTime('21/03/2023 00:00', $this->timezone);
        $seC = new LunarToSexagenaryConverter($lunar);
        $formatter = new SexagenaryFormatter($seC);

        $this->assertEquals('Mậu Thìn', $formatter->format('D+'));
        $this->assertEquals('Bính Thìn', $formatter->format('M+'));
        $this->assertEquals('Quý Mão', $formatter->format('Y+'));
        $this->assertEquals('Nhâm Tý', $formatter->format('H+'));

        $fullDate = 'Ngày Mậu Thìn, tháng Bính Thìn, năm Quý Mão, giờ Nhâm Tý';
        $formatStr = 'Ngày D+, tháng M+, năm Y+, giờ H+';
        $formatStrType2 = 'Ngày %D %d, tháng %M %m, năm %Y %y, giờ %H %h';

        $this->assertEquals($fullDate, $formatter->format($formatStr));
        $this->assertEquals($fullDate, $formatter->format($formatStrType2));
    }
}