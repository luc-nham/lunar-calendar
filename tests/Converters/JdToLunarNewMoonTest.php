<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToLunarNewMoon;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\NewMoonPhase;
use LucNham\LunarCalendar\Terms\TimeInterval;
use LucNham\LunarCalendar\Tests\Providers\VnLunarFistNewMoonList;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProviderExternal;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToLunarNewMoon::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(NewMoonPhase::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
class JdToLunarNewMoonTest extends TestCase
{
    public function testUTC()
    {
        // Expected new moon at 2024-02-09T00:00+00:00
        (new GregorianToJd(new DateTimeInterval(10, 2, 2024)))
            ->forward(function (float $jd) {
                (new JdToLunarNewMoon($jd))
                    ->forward(function (NewMoonPhase $nm) {
                        $this->assertEquals(1535, $nm->total);
                        $this->assertEquals(2460349.5, $nm->jd);
                    });
            });

        // Expected new moon at 2024-01-11T00:00+00:00
        (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 23, 59, 59)))
            ->forward(function (float $jd) {
                (new JdToLunarNewMoon($jd))
                    ->forward(function (NewMoonPhase $nm) {
                        $this->assertEquals(1535, $nm->total);
                        $this->assertEquals(2460349.5, $nm->jd);
                    });
            });
    }

    public function testEastLocal()
    {
        $offset = 25200;

        // Expected new moon at 2024-02-09T00:00+00:00
        (new GregorianToJd(new DateTimeInterval(10, 2, 2024), $offset))
            ->forward(function (float $jd) use ($offset) {
                (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) {
                        $this->assertEquals(1535, $nm->total);
                        $this->assertEquals(2460350.2083333, $nm->jd);
                    });
            });

        // Expected new moon at 2024-02-09T00:00+00:00
        // The input jd at 2024-02-09T17:00:00+0000 equal 2024-02-10T00:00+0700
        (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 17, 0, 0)))
            ->forward(function (float $jd) use ($offset) {
                (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) {
                        $this->assertEquals(1535, $nm->total);
                        $this->assertEquals(2460350.2083333, $nm->jd);
                    });
            });

        // Expected new moon at 2024-01-11T00:00+00:00
        // The input jd at 2024-02-09T16:59:59+0000 equal 2024-02-09T23:59:59+0700
        (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 16, 59, 59)))
            ->forward(function (float $jd) use ($offset) {
                (new JdToLunarNewMoon($jd, $offset))
                    ->forward(function (NewMoonPhase $nm) {
                        $this->assertEquals(1534, $nm->total);
                        $this->assertEquals(2460320.2083333, $nm->jd);
                    });
            });

        // Expected new moon at 2024-02-09T00:00+00:00
        // The input jd at 2024-02-09T10:00:00+0000 equal 2024-02-10T00:00+1400
        // GMT+14, offset 50400
        $offset = 50400;
        $jd = (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 10, 0, 0)))->getOutput();

        (new JdToLunarNewMoon($jd, $offset))
            ->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1535, $nm->total);
                $this->assertEquals(2460349.9166667, $nm->jd);
            });

        (new JdToLunarNewMoon($jd + 1, $offset))
            ->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1535, $nm->total);
                $this->assertEquals(2460349.9166667, $nm->jd);
            });

        // Expected new moon at 2024-01-11T00:00+00:00
        (new JdToLunarNewMoon($jd - 0.1, $offset))
            ->forward(function (NewMoonPhase $nm) {
                $this->assertEquals(1534, $nm->total);
                $this->assertEquals(2460320.9166667, $nm->jd);
            });
    }

    /**
     * @link https://github.com/luc-nham/lunar-calendar/issues/47
     */
    public function testFixIssue47()
    {
        $offset = 25200;        // GMT+7
        $jd = 2415050.2083333;   // 1900-01-30T17:00+0000 | 1900-01-31T00:00+0700

        $converter = new JdToLunarNewMoon($jd, $offset);
        $newmoon = $converter->getOutput();

        $this->assertEquals(1, $newmoon->total);
        $this->assertEquals(2415050.2083333, $newmoon->jd);
    }

    /**
     * @link https://github.com/luc-nham/lunar-calendar/issues/47
     */
    #[CoversNothing]
    #[DataProviderExternal(VnLunarFistNewMoonList::class, 'list')]
    public function testVnFirstNewMoon1900To2100(string $jd, int $total, string $lunar, string $gregorian)
    {
        $offset = 25200;
        $date = explode('-', $gregorian);
        $interval = new DateTimeInterval(
            (int)$date[2],
            (int)$date[1],
            (int)$date[0]
        );

        $inputJd = (new GregorianToJd($interval, $offset))->getOutput();
        $newMoon = (new JdToLunarNewMoon($inputJd, $offset))->getOutput();

        $this->assertEquals($inputJd, $newMoon->jd);
        $this->assertEquals($total, $newMoon->total);
    }
}
