<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\NewMoonToLunarFirstNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\LunarFirstNewMoonPhase;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use LucNham\LunarCalendar\Tests\Providers\VnLunarFistNewMoonList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewMoonToLunarFirstNewMoon::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(LunarFirstNewMoonPhase::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
class NewMoonToLunarFirstNewMoonTest extends TestCase
{
    public function testUnequalGregorianAndLunarYear()
    {
        // Gregorian 1970-01-08 | Lunar: 1969-12-01
        (new GregorianToJd(new DateTimeInterval(8, 1, 1970)))
            ->forward(function (float $jd) {
                (new JdToLunarNewMoon($jd))
                    ->forward(function (NewMoonPhase $nm) {
                        (new NewMoonToLunarFirstNewMoon($nm))
                            ->forward(function (LunarFirstNewMoonPhase $fnm) {
                                $this->assertEquals(855, $fnm->total);
                                $this->assertEquals(1969, $fnm->year);
                                $this->assertFalse($fnm->leap);
                            });
                    });
            });

        // Correction test
        (new GregorianToJd(new DateTimeInterval(20, 10, 1901)))
            ->forward(function (float $jd) {
                (new JdToLunarNewMoon($jd))
                    ->forward(function (NewMoonPhase $nm) {
                        (new NewMoonToLunarFirstNewMoon($nm))
                            ->forward(function (LunarFirstNewMoonPhase $fnm) {
                                $this->assertEquals(14, $fnm->total);
                                $this->assertEquals(1901, $fnm->year);
                                $this->assertFalse($fnm->leap);
                            });
                    });
            });
    }

    #[CoversNothing]
    #[DataProviderExternal(VnLunarFistNewMoonList::class, 'list')]
    public function testVnLunarCalendarFrom1900To2100(string $jd, int $total, string $lunar, string $gregorian)
    {
        $offset = 25200;
        $date = explode('-', $gregorian);
        $interval = new DateTimeInterval(
            rand(1, 31),
            rand(3, 12),
            (int)$date[0]
        );

        $inputJd = (new GregorianToJd($interval, $offset))->getOutput();

        $jds = explode(' | ', $jd);
        $newMoon = (new JdToLunarNewMoon($inputJd, $offset))->getOutput();
        $lfNewMoon = (new NewMoonToLunarFirstNewMoon($newMoon, $offset))->getOutput();

        $this->assertEquals((float)$jds[1], $lfNewMoon->jd);
    }
}
