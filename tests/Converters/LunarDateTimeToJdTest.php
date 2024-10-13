<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
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
class LunarDateTimeToJdTest extends TestCase
{
    public function testDefault()
    {
        (new LunarDateTimeToJd())
            ->then(JdToGregorian::class)
            ->forward(function (DateTimeInterval $gre) {
                $this->assertEquals(6, $gre->d);
                $this->assertEquals(2, $gre->m);
                $this->assertEquals(1970, $gre->y);
                $this->assertEquals(0, $gre->h);
                $this->assertEquals(0, $gre->i);
                $this->assertEquals(0, $gre->s);
            });
    }

    public function testIncorrectLeapInput()
    {
        // Input is a leap year, but incorrect leap month checking
        (new LunarDateTimeToJd(
            lunar: new LunarDateTimeInterval(
                d: 10,
                m: 3,
                y: 2020,
                h: 23,
                i: 59,
                s: 59,
                leap: true, // Incorrect will be igroned. Correct leap month number is 4.
            ),
            offset: 25200,
        ))
            ->then(JdToGregorian::class)
            ->forward(function (DateTimeInterval $gre) {
                $this->assertEquals(2, $gre->d);
                $this->assertEquals(4, $gre->m);
                $this->assertEquals(2020, $gre->y);
                $this->assertEquals(23, $gre->h);
                $this->assertEquals(59, $gre->i);
                $this->assertEquals(59, $gre->s);
            });

        // Input is not a leap year
        (new LunarDateTimeToJd(
            lunar: new LunarDateTimeInterval(
                d: 30,
                m: 5,
                y: 2021,
                leap: true, // Incorrect will be igroned.
                h: 0,
                i: 1,
                s: 58
            ),
            offset: 25200,
        ))
            ->then(JdToGregorian::class)
            ->forward(function (DateTimeInterval $gre) {
                $this->assertEquals(9, $gre->d);
                $this->assertEquals(7, $gre->m);
                $this->assertEquals(2021, $gre->y);
                $this->assertEquals(0, $gre->h);
                $this->assertEquals(1, $gre->i);
                $this->assertEquals(58, $gre->s);
            });
    }

    public function testCorrectedLeapInput()
    {
        // Input is a leap year, and corrected leap month checking
        (new LunarDateTimeToJd(
            lunar: new LunarDateTimeInterval(
                d: 1,
                m: 4,
                y: 2020,
                h: 1,
                i: 1,
                s: 1,
                leap: true,
            ),
            offset: 25200,
        ))
            ->then(JdToGregorian::class)
            ->forward(function (DateTimeInterval $gre) {
                $this->assertEquals(23, $gre->d);
                $this->assertEquals(5, $gre->m);
                $this->assertEquals(2020, $gre->y);
                $this->assertEquals(1, $gre->h);
                $this->assertEquals(1, $gre->i);
                $this->assertEquals(1, $gre->s);
            });
    }
}
