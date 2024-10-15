<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
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
class LunarUnsafeToLunarGuaranteedTest extends TestCase
{
    public function testIncorrectDayNumber()
    {
        $lunar = (new LunarUnsafeToLunarGuaranteed(
            lunar: new LunarDateTimeInterval(
                d: 35,
                m: 12,
                y: 1970
            ),
            offset: 25200
        ))->getOutput();

        $this->assertEquals(5, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1971, $lunar->y);
    }

    public function testIncorrectMonthNumber()
    {
        $lunar = (new LunarUnsafeToLunarGuaranteed(
            lunar: new LunarDateTimeInterval(
                d: 1,
                m: 13,
                y: 1970
            ),
            offset: 25200
        ))->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1971, $lunar->y);
    }

    public function testIncorrectLeapMonthCheck()
    {
        $lunar = (new LunarUnsafeToLunarGuaranteed(
            lunar: new LunarDateTimeInterval(
                d: 3,
                m: 5,
                y: 1974,
                leap: true
            ),
            offset: 25200
        ))->getGuaranteedLunarDateTime();

        $this->assertEquals(3, $lunar->d);
        $this->assertEquals(5, $lunar->m);
        $this->assertEquals(1974, $lunar->y);
        $this->assertEquals(4, $lunar->l);
        $this->assertFalse($lunar->leap);
    }

    public function testIncorrectSeconds()
    {
        $lunar = (new LunarUnsafeToLunarGuaranteed(
            lunar: new LunarDateTimeInterval(
                d: 30,
                m: 4,
                y: 1974,
                h: 23,
                i: 59,
                s: 60
            ),
            offset: 25200
        ))->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(4, $lunar->m);
        $this->assertEquals(1974, $lunar->y);
        $this->assertEquals(0, $lunar->h);
        $this->assertEquals(0, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(4, $lunar->l);
        $this->assertTrue($lunar->leap);
    }
}
