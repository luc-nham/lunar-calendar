<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\JdnToUtcGregorian;
use VanTran\LunarCalendar\Converters\GregorianToJDNConverter;
use VanTran\LunarCalendar\Converters\JdnToLocalGregorian;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

class JdnToLocalGregorianTest extends TestCase
{
    public function testOuput(): void
    {
        $year = 2023;
        $month = 10;
        $day = 2;
        $hour = 6;
        $offset = 25200; // Việt Nam

        $jd = new GregorianToJDNConverter(
            $year,
            $month,
            $day,
            $hour,
            1,
            1,
            $offset
        );

        $localCvt = new JdnToLocalGregorian($jd);

        // UTC tương ứng
        $utcCvt = new JdnToUtcGregorian($jd->getJd());

        $this->assertEquals($year, $localCvt->getYear());
        $this->assertEquals($month, $localCvt->getMonth());
        $this->assertEquals($day, $localCvt->getDay());
        $this->assertEquals($hour, $localCvt->getHour());
        $this->assertEquals('+07:00', $localCvt->getTimezone()->getName());

        // 6 giờ sáng tại Việt Nam tương ứng với 23 giờ đêm ngày hôm trước theo UTC
        $this->assertEquals($day - 1, $utcCvt->getDay());
        $this->assertEquals(23, $utcCvt->getHour());
    }
}