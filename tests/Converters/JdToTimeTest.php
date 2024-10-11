<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use DateInterval;
use DateTime;
use LucNham\LunarCalendar\Converters\GregorianToJd;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
#[CoversClass(GregorianToJd::class)]
#[CoversClass(DateTimeInterval::class)]
class JdToTimeTest extends TestCase
{
    public function testDefault()
    {
        $converter = new JdToTime();
        $time = $converter->getOutput();

        $this->assertEquals(0, $time->h);
        $this->assertEquals(0, $time->i);
        $this->assertEquals(0, $time->s);

        // GMT-7
        $time = $converter->setOffset(-25200)->getOutput();

        $this->assertEquals(17, $time->h);
        $this->assertEquals(0, $time->i);
        $this->assertEquals(0, $time->s);

        // GTM+7.5
        $time = $converter->setOffset(7.5 * 60 * 60)->getOutput();
        $this->assertEquals(7, $time->h);
        $this->assertEquals(30, $time->i);
        $this->assertEquals(0, $time->s);
    }

    public function testEndOfDay()
    {
        $converter = new JdToTime(2460350.499988888);
        $time = $converter->getOutput();

        $this->assertEquals(23, $time->h);
        $this->assertEquals(59, $time->i);
        $this->assertEquals(59, $time->s);
    }

    public function testSmallPeriodOfTime()
    {
        $time = (new GregorianToJd(new DateTimeInterval(
            y: 2024,
            h: 8,
            i: 59,
            s: 58
        )))
            ->then(JdToTime::class)
            ->getOutput();

        $this->assertEquals(8, $time->h);
        $this->assertEquals(59, $time->i);
        $this->assertEquals(58, $time->s);

        $time = (new GregorianToJd(new DateTimeInterval(
            y: 2024,
            h: 1,
            i: 0,
            s: 1
        )))
            ->then(JdToTime::class)
            ->getOutput();

        $this->assertEquals(1, $time->h);
        $this->assertEquals(0, $time->i);
        $this->assertEquals(1, $time->s);

        // Direct modify Julian day number test

        $jd = (new GregorianToJd(new DateTimeInterval(
            y: 2024,
            h: 1,
            i: 0,
            s: 1
        )))->getOutput();

        // Add one second
        (new JdToTime($jd + 0.00001157407))
            ->forward(function (TimeInterval $time) {
                $this->assertEquals(1, $time->h);
                $this->assertEquals(0, $time->i);
                $this->assertEquals(2, $time->s);
            });

        // Subtract 2 seconds
        (new JdToTime($jd - 0.00001157407 * 2))
            ->forward(function (TimeInterval $time) {
                $this->assertEquals(0, $time->h);
                $this->assertEquals(59, $time->i);
                $this->assertEquals(59, $time->s);
            });
    }

    /**
     * A large test with 86400 loops to test each second increment.
     */
    public function testOneDay()
    {
        $date = new DateTime('1970-01-01T00:00:00+0000');

        $h = (int)$date->format('G');
        $i = (int)$date->format('i');
        $s = (int)$date->format('s');

        for ($k = 1; $k <= 86399; $k++) {
            (new GregorianToJd(
                new DateTimeInterval(
                    d: 1,
                    m: 1,
                    y: 1970,
                    h: $h,
                    i: $i,
                    s: $s,
                )
            ))
                ->then(JdToTime::class)
                ->forward(function (TimeInterval $time) use ($h, $i, $s) {
                    $this->assertEquals($h, $time->h);
                    $this->assertEquals($i, $time->i);
                    $this->assertEquals($s, $time->s);
                });

            $date->add(new DateInterval('PT1S'));
        }
    }
}
