<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToLunarDateTime::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(LunarLeapMonthNewMoonPhase::class)]
#[CoversClass(LunarDateTimeGuaranteed::class)]
class JdToLunarDateTimeTest extends TestCase
{
    public function testDefault()
    {
        $converter = new JdToLunarDateTime(offset: 0);
        $lunar = $converter->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1970, $lunar->y);
        $this->assertEquals(0, $lunar->h);
        $this->assertEquals(0, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(0, $lunar->l);          // 1970 is not a Lunar leap year
        $this->assertEquals(2440623.5, $lunar->j);

        $converter->setOffset(25200);
        $lunar = $converter->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1970, $lunar->y);
        $this->assertEquals(7, $lunar->h);
        $this->assertEquals(0, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(0, $lunar->l);          // 1970 is not a Lunar leap year

        $guaranteed = $converter->getGuaranteedLunarDateTime();
        $this->assertInstanceOf(LunarDateTimeGuaranteed::class, $guaranteed);
    }

    public function testBeforeLeapMonth()
    {
        /** @var LunarDateTimeGuaranteed */
        $lunar = ((new GregorianToJd(
            g: new DateTimeInterval(
                d: 21,
                m: 3,
                y: 2023,
                h: 22,
                i: 50,
                s: 59
            ),
            offset: 25200
        )))
            ->then(JdToLunarDateTime::class)
            ->getOutput();

        $this->assertEquals(30, $lunar->d);
        $this->assertEquals(2, $lunar->m);
        $this->assertEquals(2023, $lunar->y);
        $this->assertEquals(22, $lunar->h);
        $this->assertEquals(50, $lunar->i);
        $this->assertEquals(59, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(2, $lunar->l);
    }

    public function testAfterLeapMonth()
    {
        /** @var LunarDateTimeGuaranteed */
        $lunar = ((new GregorianToJd(
            g: new DateTimeInterval(
                d: 18,
                m: 2,
                y: 2034,
                h: 1,
                i: 1,
                s: 57
            ),
            offset: 25200
        )))
            ->then(JdToLunarDateTime::class)
            ->getOutput();

        $this->assertEquals(30, $lunar->d);
        $this->assertEquals(12, $lunar->m);
        $this->assertEquals(2033, $lunar->y);
        $this->assertEquals(1, $lunar->h);
        $this->assertEquals(1, $lunar->i);
        $this->assertEquals(57, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(11, $lunar->l);
    }

    public function testInLeapMonth()
    {
        /** @var LunarDateTimeGuaranteed */
        $lunar = ((new GregorianToJd(
            g: new DateTimeInterval(
                d: 24,
                m: 10,
                y: 2014,
                h: 22,
                i: 49,
                s: 56
            ),
            offset: 25200
        )))
            ->then(JdToLunarDateTime::class)
            ->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(9, $lunar->m);
        $this->assertEquals(2014, $lunar->y);
        $this->assertEquals(22, $lunar->h);
        $this->assertEquals(49, $lunar->i);
        $this->assertEquals(56, $lunar->s);
        $this->assertEquals(true, $lunar->leap);
        $this->assertEquals(9, $lunar->l);
    }
}
