<?php

namespace LucNham\LunarCalendar\Tests;

use DateTime;
use LucNham\LunarCalendar\Converters\DateTimeIntervalToDateTimeString;
use LucNham\LunarCalendar\Converters\DateTimeStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\DateTimeToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\GregorianToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToDateTimeString;
use LucNham\LunarCalendar\Converters\LunarDateTimeToGregorian;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\LunarStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\LunarUnsafeToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Formatters\LunarDateTimeDefaultFormatter;
use LucNham\LunarCalendar\LunarDateTime;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarDateTime::class)]
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
#[CoversClass(DateTimeStringToLunarGuaranteed::class)]
#[CoversClass(DateTimeToLunarGuaranteed::class)]
#[CoversClass(GregorianToLunarDateTime::class)]
#[CoversClass(LunarDateTimeDefaultFormatter::class)]
#[CoversClass(LunarDateTimeToDateTimeString::class)]
#[CoversClass(LunarDateTimeToGregorian::class)]
#[CoversClass(DateTimeIntervalToDateTimeString::class)]
class LunarDateTimeTest extends TestCase
{
    public function testConstructor()
    {
        // From lunar string
        $lunar = new LunarDateTime('01/01/1970');
        $this->assertEquals('1970-01-01', $lunar->format('Y-m-d'));

        // From Gregorian string
        $lunar = new LunarDateTime('G:1970-02-06');
        $this->assertEquals('1970-01-01', $lunar->format('Y-m-d'));

        // Invalid date time string
        $this->expectExceptionMessage('Invalid date time format');
        new LunarDateTime('1900-13-02'); // Invalid month number
    }

    public function testGetGuaranteedLunarDateTime()
    {
        $lunar = new LunarDateTime();
        $this->assertInstanceOf(LunarDateTimeGuaranteed::class, $lunar->getGuaranteedLunarDateTime());
    }

    public function testGetTimestamp()
    {
        $lunar = new LunarDateTime('G:1970-01-01 +0000');
        $this->assertEquals(0, $lunar->getTimestamp());
    }

    public function testGetTimezone()
    {
        $lunar = new LunarDateTime('G:1970-01-01 UTC');
        $this->assertEquals('UTC', $lunar->getTimezone()->getName());
    }

    public function testGetOffset()
    {
        $lunar = new LunarDateTime('G:1970-01-01 UTC');
        $this->assertEquals(0, $lunar->getOffset());
    }

    public function testGetInstanceFromGregorian()
    {
        $lunar = LunarDateTime::fromGregorian('2024-02-10 +0700');
        $this->assertEquals('01/01/2024', $lunar->format('d/m/Y'));
    }

    public function testGetInstanceNow()
    {
        $date = new DateTime();
        $lunar1 = LunarDateTime::now();
        $lunar2 = LunarDateTime::fromGregorian($date->format('Y-m-d H:i P'));

        $this->assertEquals($lunar2->format('Y-m-d H:i P'), $lunar1->format('Y-m-d H:i P'));
    }

    public function testToDateTimeString()
    {
        $lunar = new LunarDateTime('2024-01-01 +0700');
        $this->assertEquals('2024-02-10 00:00:00 +07:00', $lunar->toDateTimeString());

        $lunar = new LunarDateTime('2024-01-01 -12:00');
        $this->assertEquals('2024-02-09 00:00:00 -12:00', $lunar->toDateTimeString());
    }

    public function test_magic_getter()
    {
        $lunar = new LunarDateTime();

        $this->assertIsInt($lunar->day);
        $this->assertIsInt($lunar->month);
        $this->assertIsInt($lunar->year);
        $this->assertIsInt($lunar->hour);
        $this->assertIsInt($lunar->minute);
        $this->assertIsInt($lunar->second);
        $this->assertIsInt($lunar->timestamp);
        $this->assertIsFloat($lunar->jdn);
        $this->assertIsInt($lunar->leap);
        $this->assertIsBool($lunar->isLeapMonth);
    }
}
