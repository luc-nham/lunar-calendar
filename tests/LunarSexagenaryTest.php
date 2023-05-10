<?php namespace VanTran\LunarCalendar\Tests;

use DateTimeZone;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\LunarDateTime;
use VanTran\LunarCalendar\LunarSexagenary;

class LunarSexagenaryTest extends TestCase
{
    public function testFormat(): void
    {
        $this->assertTrue(true);

        $lunar = LunarDateTime::createFromGregorian('2023-04-30 13:00 +07:00');
        $sexagenary = new LunarSexagenary($lunar);

        $expected = 'Ngày Mậu Ngọ, tháng Bính Thìn, năm Quý Mão, giờ Kỷ Mùi';
        $format = 'Ngày D+, tháng M+, năm Y+, giờ H+';

        $this->assertEquals($expected, $sexagenary->format($format));
    }
}