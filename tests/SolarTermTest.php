<?php namespace VanTran\LunarCalendar\Tests;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\SolarTerm;

class SolarTermTest extends TestCase
{
    public function testNow(): void
    {
        $t1 = new SolarTerm();
        $t2 = SolarTerm::now();

        $this->assertEquals($t1->getDegrees(), $t2->getDegrees());
    }
}