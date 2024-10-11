<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Converters\JdToTime;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use LucNham\LunarCalendar\Terms\TimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToGregorian::class)]
#[CoversClass(DateTimeInterval::class)]
#[CoversClass(JdToTime::class)]
#[CoversClass(TimeInterval::class)]
class JdToGregorianTest extends TestCase
{
    public function testUTC()
    {
        $c = new JdToGregorian(); // Default input
        $o = $c->getOutput();

        $this->assertEquals(1, $o->d);
        $this->assertEquals(1, $o->m);
        $this->assertEquals(1970, $o->y);
        $this->assertEquals(0, $o->h);
        $this->assertEquals(0, $o->i);
        $this->assertEquals(0, $o->s);

        // Change input then get new output
        // 2024-09-26T05:45:12+0000
        $c->setInput(2460579.739722)->forward(function ($o) {
            $this->assertEquals(26, $o->d);
            $this->assertEquals(9, $o->m);
            $this->assertEquals(2024, $o->y);
            $this->assertEquals(5, $o->h);
            $this->assertEquals(45, $o->i);
            $this->assertEquals(12, $o->s);
        });
    }

    public function testLocalTime()
    {
        // Via constructor
        // 1990-12-10T12:52:37+0000 | 1990-12-10T19:52:37+0700
        $c = new JdToGregorian(2448236.036539, 25200);

        $c->forward(function ($o) {
            $this->assertEquals(10, $o->d);
            $this->assertEquals(12, $o->m);
            $this->assertEquals(1990, $o->y);
            $this->assertEquals(19, $o->h);
            $this->assertEquals(52, $o->i);
            $this->assertEquals(37, $o->s);
        });

        // Via method
        $c->setOffset(0)->forward(function ($o) {
            $this->assertEquals(10, $o->d);
            $this->assertEquals(12, $o->m);
            $this->assertEquals(1990, $o->y);
            $this->assertEquals(12, $o->h);
            $this->assertEquals(52, $o->i);
            $this->assertEquals(37, $o->s);
        });

        $c->setOffset(25200)->forward(function ($o) {
            $this->assertEquals(19, $o->h);
        });
    }

    /**
     * @link https://github.com/luc-nham/lunar-calendar/issues/44
     */
    public function testFixIssue44()
    {
        $offset = 25200;        // GMT+7 
        $jd = 2415050.2083333;  // 1900-01-30T17:00+0000
        $converter = new JdToGregorian($jd, $offset);
        $gregorian = $converter->getOutput();

        // Expect local time 1900-01-31T00:00+0700
        $this->assertEquals(31, $gregorian->d);
        $this->assertEquals(1, $gregorian->m);
        $this->assertEquals(1900, $gregorian->y);
        $this->assertEquals(0, $gregorian->h);
        $this->assertEquals(0, $gregorian->i);
        $this->assertEquals(0, $gregorian->s);

        // Expect local time 1900-01-30T23:59:59+0700
        // Decrement the input value by 1 second
        $jd2 = $jd - 0.00001157407;
        $gregorian = $converter->setInput($jd2)->getOutput();
        $this->assertEquals(30, $gregorian->d);
        $this->assertEquals(1, $gregorian->m);
        $this->assertEquals(1900, $gregorian->y);
        $this->assertEquals(23, $gregorian->h);
        $this->assertEquals(59, $gregorian->i);
        $this->assertEquals(59, $gregorian->s);

        // Expect local time 1900-01-31T00:00:01+0700
        (new JdToGregorian(2415050.208345, $offset))
            ->forward(function (DateTimeInterval $date) {
                $this->assertEquals(31, $date->d);
                $this->assertEquals(1, $date->m);
                $this->assertEquals(1900, $date->y);
                $this->assertEquals(0, $date->h);
                $this->assertEquals(0, $date->i);
                $this->assertEquals(1, $date->s);
            });
    }
}
