<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\LunarDateTimeToGregorian;
use LucNham\LunarCalendar\Converters\LunarDateTimeToJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarDateTimeToGregorian::class)]
#[CoversClass(LunarDateTimeToJd::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(NewMoonIterator::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(LunarDateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(LunarLeapMonthNewMoonPhase::class)]
class LunarDateTimeToGregorianTest extends TestCase
{
    public function testDefault()
    {
        (new LunarDateTimeToGregorian())
            ->forward(function (DateTimeInterval $gre) {
                $this->assertEquals(6, $gre->d);
                $this->assertEquals(2, $gre->m);
                $this->assertEquals(1970, $gre->y);
                $this->assertEquals(0, $gre->h);
                $this->assertEquals(0, $gre->i);
                $this->assertEquals(0, $gre->s);
            });
    }
}
