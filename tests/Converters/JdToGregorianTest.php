<?php

namespace LucNham\LunarCalendar\Tests\Converters;

use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JdToGregorian::class)]
#[CoversClass(DateTimeInterval::class)]
class JdToGregorianTest extends TestCase
{
    public function testUTC()
    {
        $c = new JdToGregorian(); // Default input
        $o = $c->getOuput();

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
}
