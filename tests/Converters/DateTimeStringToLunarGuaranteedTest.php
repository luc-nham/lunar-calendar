<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use DateTimeZone;
use LucNham\LunarCalendar\Converters\DateTimeStringToLunarGuaranteed;
use LucNham\LunarCalendar\Converters\DateTimeToLunarGuaranteed;
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
#[CoversClass(DateTimeStringToLunarGuaranteed::class)]
#[CoversClass(DateTimeToLunarGuaranteed::class)]
class DateTimeStringToLunarGuaranteedTest extends TestCase
{
    public function testDateTime()
    {
        $lunar = (new DateTimeStringToLunarGuaranteed('2024-02-10 +0700'))->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(2024, $lunar->y);
    }

    public function testTimezone()
    {
        $converter = new DateTimeStringToLunarGuaranteed('2020-01-01', new DateTimeZone('UTC'));
        $timezone = $converter->getTimezone();

        $this->assertEquals('UTC', $timezone->getName());
        $this->assertEquals(0, $converter->getOffset());
    }
}
