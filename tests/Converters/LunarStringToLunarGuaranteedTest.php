<?php

namespace LucNham\LunarCalendar\Tests\Parsers;

use DateMalformedStringException;
use DateTimeZone;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\LunarStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\LunarUnsafeToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarStringToLunarGuaranteed::class)]
#[CoversClass(LunarUnsafeToLunarGuaranteed::class)]
#[CoversClass(JdToLunarDateTime::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(LunarDateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(LunarLeapMonthNewMoonPhase::class)]
#[CoversClass(LunarDateTimeToJd::class)]
#[CoversClass(LunarDateTimeGuaranteed::class)]
class LunarStringToLunarGuaranteedTest extends TestCase
{
    public function testDateTime()
    {
        $converter = (new LunarStringToLunarGuaranteed('2024-10-20 20:30'));
        $lunar = $converter->getGuaranteedLunarDateTime();

        $this->assertEquals(20, $lunar->d);
        $this->assertEquals(10, $lunar->m);
        $this->assertEquals(2024, $lunar->y);
        $this->assertEquals(20, $lunar->h);
        $this->assertEquals(30, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
    }

    public function testDefaultTimeZone()
    {
        $converter = (new LunarStringToLunarGuaranteed('2024-10-20 20:30'));
        $timezone = new DateTimeZone(date_default_timezone_get());

        $this->assertEquals($timezone->getName(), $converter->getTimezone()->getName());
    }

    public function testIncludeTimeZone()
    {
        // Timezone without ID
        $converter = (new LunarStringToLunarGuaranteed('2024-10-20 +0700'));

        $this->assertEquals('+07:00', $converter->getTimezone()->getName());
        $this->assertEquals(25200, $converter->getOffset());

        $converter = (new LunarStringToLunarGuaranteed('2024-10-20 -1200'));
        $this->assertEquals('-12:00', $converter->getTimezone()->getName());

        // Timezone with ID
        $converter = new LunarStringToLunarGuaranteed('2024-10-20 Asia/Ho_Chi_Minh');
        $timezone = $converter->getTimezone();

        $this->assertEquals('Asia/Ho_Chi_Minh', $timezone->getName());
        $this->assertEquals(25200, $converter->offset());

        $converter = new LunarStringToLunarGuaranteed('1945-02-01 Asia/Ho_Chi_Minh');
        $timezone = $converter->getTimezone();

        $this->assertEquals('Asia/Ho_Chi_Minh', $timezone->getName());
        $this->assertEquals(8 * 3600, $converter->offset());

        // Time zone included in the input string have higher priority
        $timezone = new DateTimeZone('UTC');
        $converter = new LunarStringToLunarGuaranteed('2024-10-20 +0700', $timezone);

        $this->assertEquals('+07:00', $converter->getTimezone()->getName());

        // If the time zone is not included in the input string, the time zone parameter will be used
        $converter = new LunarStringToLunarGuaranteed('2024-10-20', $timezone);

        $this->assertEquals('UTC', $converter->getTimezone()->getName());
    }

    public function testLeapMonthSigns()
    {
        $timezone = new DateTimeZone('GMT+7');

        $list = [
            new LunarStringToLunarGuaranteed('2028-05+-11', $timezone),
            new LunarStringToLunarGuaranteed('11/05+/2028', $timezone),
            new LunarStringToLunarGuaranteed('2028-05-11 +0700 (+)'),
            new LunarStringToLunarGuaranteed('2028-05-11 10:20:30 +0700 [+]'),
        ];

        foreach ($list as $converter) {
            $lunar = $converter->getOutput();

            $this->assertEquals(11, $lunar->d);
            $this->assertEquals(5, $lunar->m);
            $this->assertEquals(2028, $lunar->y);
            $this->assertTrue($lunar->leap);
        }
    }

    public function testInvalidLunarString()
    {
        $this->expectException(DateMalformedStringException::class);
        new LunarStringToLunarGuaranteed('1900-02-35');
    }
}
