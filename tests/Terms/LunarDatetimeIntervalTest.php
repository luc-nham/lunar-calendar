<?php

namespace LucNham\LunarCalendar\Tests\Terms;

use LucNham\LunarCalendar\Terms\LunarDateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(LunarDateTimeInterval::class)]
class LunarDatetimeIntervalTest extends TestCase
{
    public function testDefaultValue()
    {
        $date = new LunarDateTimeInterval();

        $this->assertEquals(1, $date->d);
        $this->assertEquals(1, $date->m);
        $this->assertEquals(1970, $date->y);
        $this->assertEquals(0, $date->h);
        $this->assertEquals(0, $date->i);
        $this->assertEquals(0, $date->s);
        $this->assertEquals(0, $date->l);
        $this->assertEquals(false, $date->leap);
    }

    public function testCreateViaConstructor()
    {
        $date = new LunarDateTimeInterval(
            d: 20,
            y: 2024,
            m: 10,
            s: 59,
            leap: true,
            l: 6
        );

        $this->assertEquals(20, $date->d);
        $this->assertEquals(10, $date->m);
        $this->assertEquals(2024, $date->y);
        $this->assertEquals(0, $date->h);
        $this->assertEquals(0, $date->i);
        $this->assertEquals(59, $date->s);
        $this->assertEquals(6, $date->l);
        $this->assertEquals(true, $date->leap);
    }
}
