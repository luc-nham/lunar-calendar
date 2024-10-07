<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLs;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\LunarFirstNewMoonToLunarLeapNewMoon;
use LucNham\LunarCalendar\Converters\NewMoonIterator;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\LunarLeapMonthNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Tests\Providers\VnLunarLeapMonthProvider;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(GregorianToJd::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(LunarFirstNewMoonToLunarLeapNewMoon::class)]
#[CoversClass(LunarLeapMonthNewMoonPhase::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToLs::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(NewMoonIterator::class)]
class LunarFirstNewMoonToLunarLeapNewMoonTest extends TestCase
{
    #[CoversNothing]
    #[DataProviderExternal(VnLunarLeapMonthProvider::class, 'listOf20thCentury')]
    public function testVn20thCenturyLeapMonth(int $year, int $month, float $jd)
    {
        $offset = 25200;

        /** @var LunarLeapMonthNewMoonPhase */
        $leap = (new GregorianToJd(new DateTimeInterval(1, 10, $year)))
            ->forward(function (float $jd) use ($offset) {
                return (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) use ($offset) {
                        return (new NewMoonToLunarFirstNewMoon($nm, $offset))
                            ->forward(function (LunarFirstNewMoonPhase $fnm) use ($offset) {
                                return (new LunarFirstNewMoonToLunarLeapNewMoon($fnm, $offset))
                                    ->getOutput();
                            });
                    });
            });

        $this->assertEquals($month, $leap->month);
        $this->assertEquals($jd, $leap->jd);
    }

    #[DataProviderExternal(VnLunarLeapMonthProvider::class, 'listOf21thCentury')]
    public function testVn21thCenturyLeapMonth(int $year, int $month, float $jd)
    {
        $offset = 25200;

        /** @var LunarLeapMonthNewMoonPhase */
        $leap = (new GregorianToJd(new DateTimeInterval(1, 10, $year)))
            ->forward(function (float $jd) use ($offset) {
                return (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) use ($offset) {
                        return (new NewMoonToLunarFirstNewMoon($nm, $offset))
                            ->forward(function (LunarFirstNewMoonPhase $fnm) use ($offset) {
                                return (new LunarFirstNewMoonToLunarLeapNewMoon($fnm, $offset))
                                    ->getOutput();
                            });
                    });
            });

        $this->assertEquals($month, $leap->month);
        $this->assertEquals($jd, $leap->jd);
    }

    public function testUnleapYear()
    {
        $offset = 25200;

        /** @var null */
        $leap = (new GregorianToJd(new DateTimeInterval(1, 10, 2024)))
            ->forward(function (float $jd) use ($offset) {
                return (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) use ($offset) {
                        return (new NewMoonToLunarFirstNewMoon($nm, $offset))
                            ->forward(function (LunarFirstNewMoonPhase $fnm) use ($offset) {
                                return (new LunarFirstNewMoonToLunarLeapNewMoon($fnm, $offset))
                                    ->getOutput();
                            });
                    });
            });

        $this->assertNull($leap);
    }
}
