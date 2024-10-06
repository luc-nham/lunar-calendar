<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToMidnightJd;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToMidnightJd::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(GregorianToJd::class)]
class JdToMidnightJdTest extends TestCase
{
    public function testDefault()
    {
        // Default input 1970-01-01T00:00+0000
        $c = new JdToMidnightJd();

        $c->forward(fn($j) => $this->assertEquals(2440587.5, $j));

        return $c;
    }

    #[Depends('testDefault')]
    public function testChangeJdInput(JdToMidnightJd $c)
    {
        // Change input to 1970-01-01T12:00+0000
        $c->setJd(2440587.5 + 0.5)->forward(fn($j) => $this->assertEquals(2440587.5, $j));

        return $c;
    }

    #[Depends('testChangeJdInput')]
    public function testChangeOffset(JdToMidnightJd $c)
    {
        // Change offset to 1970-01-01T12:00+0700
        $c->setOffset(25200)->forward(fn($j) => $this->assertEquals(2440587.2083333, $j));

        // Change offset to 1970-01-01T12:00-0700
        $c->setOffset(-25200)->forward(fn($j) => $this->assertEquals(2440587.7916667, $j));
    }

    public function testInputIsLocalMidnight()
    {
        (new JdToMidnightJd(2440587.2083333, 25200))
            ->forward(fn($j) => $this->assertEquals(2440587.2083333, $j));

        (new JdToMidnightJd(2440587.7916667, -25200))
            ->forward(fn($j) => $this->assertEquals(2440587.7916667, $j));
    }

    public function testToCoverage()
    {
        (new GregorianToJd(new DateTimeInterval(1, 1, 1970, 1), 25200))
            ->forward(function (float $j) {
                (new JdToMidnightJd($j, 25200))
                    ->forward(fn(float $j) => $this->assertEquals(2440587.2083333, $j));
            });
    }

    /**
     * @link https://github.com/luc-nham/lunar-calendar/issues/41
     */
    public function testIssues41()
    {
        $offset = -43200; // GMT-12

        (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 12), $offset))
            ->forward(fn(float $jd) => $this->assertEquals(2460350.5, $jd));

        (new JdToMidnightJd(2460350.5, $offset))
            ->forward(fn(float $jd) => $this->assertEquals(2460350.0, $jd));

        (new JdToMidnightJd(2460351, $offset))
            ->forward(fn(float $jd) => $this->assertEquals(2460351, $jd));

        // More test with GMT+12
        $offset = 43200;

        (new GregorianToJd(new DateTimeInterval(9, 2, 2024, 12)))
            ->forward(function (float $jd) use ($offset) {
                (new JdToMidnightJd($jd, $offset))
                    ->forward(fn(float $jd) => $this->assertEquals(2460350.0, $jd));
            });
    }

    /**
     * @link https://github.com/luc-nham/lunar-calendar/issues/47
     */
    public function testFixIssue47()
    {
        $offset = 25200;        // GMT+7
        $jd = 2415050.208333;   // 1900-01-30T17:00+0000 | 1900-01-31T00:00+0700 (fixed 6)

        (new JdToMidnightJd($jd, $offset))
            ->setFixed(6)
            ->forward(fn(float $j) => $this->assertEquals($jd, $j));
    }
}
