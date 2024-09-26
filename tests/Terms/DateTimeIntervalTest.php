<?php

namespace LucNham\LunarCalendar\Tests\Terms;

use LucNham\LunarCalendar\Terms\DateTimeInterval;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(DateTimeInterval::class)]
class DateTimeIntervalTest extends TestCase
{
    public function testDefault()
    {
        $date = new DateTimeInterval();

        $this->assertEquals(1, $date->d);
        $this->assertEquals(1, $date->m);
        $this->assertEquals(1970, $date->y);
        $this->assertEquals(0, $date->h);
        $this->assertEquals(0, $date->i);
        $this->assertEquals(0, $date->s);
    }

    public function testInsertValue()
    {
        $date = new DateTimeInterval(2, 10, 2024, 10, 30, 20);

        $this->assertEquals(2, $date->d);
        $this->assertEquals(10, $date->m);
        $this->assertEquals(2024, $date->y);
        $this->assertEquals(10, $date->h);
        $this->assertEquals(30, $date->i);
        $this->assertEquals(20, $date->s);
    }
}
