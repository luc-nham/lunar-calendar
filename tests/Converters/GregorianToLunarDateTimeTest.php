<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\GregorianToLunarDateTime;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeGuaranteed;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GregorianToLunarDateTime::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(LunarDateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(LunarDateTimeGuaranteed::class)]
class GregorianToLunarDateTimeTest extends TestCase
{
    public function testDefault()
    {
        (new GregorianToLunarDateTime())
            ->forward(function (LunarDateTimeInterval $lunar) {
                $this->assertEquals(24, $lunar->d);
                $this->assertEquals(11, $lunar->m);
                $this->assertEquals(1969, $lunar->y);
                $this->assertEquals(0, $lunar->h);
                $this->assertEquals(0, $lunar->i);
                $this->assertEquals(0, $lunar->s);
                $this->assertEquals(false, $lunar->leap);
                $this->assertEquals(0, $lunar->l);
            });
    }

    public function testCustom()
    {
        $converter = new GregorianToLunarDateTime(
            gregorian: new DateTimeInterval(
                d: 6,
                m: 2,
                y: 1970,
                h: 17
            ),
            offset: -25200 // GMT-7
        );
        $lunar = $converter->getOutput();

        $this->assertEquals(1, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1970, $lunar->y);
        $this->assertEquals(17, $lunar->h);
        $this->assertEquals(0, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(0, $lunar->l);

        // Change offset to UTC
        $lunar = $converter->setOffset(0)->getOutput();

        $this->assertEquals(2, $lunar->d);
        $this->assertEquals(1, $lunar->m);
        $this->assertEquals(1970, $lunar->y);
        $this->assertEquals(0, $lunar->h);
        $this->assertEquals(0, $lunar->i);
        $this->assertEquals(0, $lunar->s);
        $this->assertEquals(false, $lunar->leap);
        $this->assertEquals(0, $lunar->l);
    }
}
