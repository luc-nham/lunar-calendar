<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToNewMoon;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Converters\ToNewMoon;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(NewMoonPhase::class)]
#[CoversClass(ToNewMoon::class)]
#[CoversClass(JdToNewMoon::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
class JdToNewMoonTest extends TestCase
{
    public function testUtcInput()
    {
        // Expected new moon at 2024-02-09 23:00:44 +00:00
        (new GregorianToJd(
            new DateTimeInterval(10, 2, 2024)
        ))->forward(function ($jd) {
            (new JdToNewMoon($jd))->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1535, $nm->total);
                $this->assertEquals(2460350.4588525, $nm->jd);
            });
        });

        (new GregorianToJd(
            new DateTimeInterval(9, 2, 2024, 23, 0, 45)
        ))->forward(function ($jd) {
            (new JdToNewMoon($jd))->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1535, $nm->total);
                $this->assertEquals(2460350.4588525, $nm->jd);
            });
        });

        // Expected previous new moon at 2024-01-11 11:58:05 +00:00
        (new GregorianToJd(
            new DateTimeInterval(9, 2, 2024)
        ))->forward(function ($jd) {
            (new JdToNewMoon($jd))->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1534, $nm->total);
                $this->assertEquals(2460320.9986786, $nm->jd);
            });
        });
    }

    public function testEastLocalInput()
    {
        $offset = 25200;

        // Expected new moon at 2024-02-09 23:00:44 +00:00
        (new GregorianToJd(
            new DateTimeInterval(10, 2, 2024, 6, 0, 45),
            $offset
        ))->forward(function ($jd) use ($offset) {
            (new JdToNewMoon($jd))->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1535, $nm->total);
                $this->assertEquals(2460350.4588525, $nm->jd);
            });
        });

        // Expected previous new moon at 2024-01-11 11:58:05 +00:00
        (new GregorianToJd(
            new DateTimeInterval(10, 2, 2024, 0),
            $offset
        ))->forward(function ($jd) use ($offset) {
            (new JdToNewMoon($jd))->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1534, $nm->total);
                $this->assertEquals(2460320.9986786, $nm->jd);
            });
        });
    }
}
