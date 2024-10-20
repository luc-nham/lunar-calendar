<?php

namespace LucNham\LunarCalendar\Tests\Terms;

use LucNham\LunarCalendar\Converters\DateTimeIntervalToDateTimeString;
use LucNham\LunarCalendar\Converters\JdToGregorian;
use LucNham\LunarCalendar\Terms\SolarTermMilestone;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SolarTermMilestone::class)]
#[CoversClass(JdToGregorian::class)]
#[CoversClass(DateTimeIntervalToDateTimeString::class)]
class SolarTermMilestoneTest extends TestCase
{
    public function testGetMagic()
    {
        // 1970-01-01 00:00 +0000
        $jd = 2440587.5;
        $milestone = new SolarTermMilestone(
            jd: $jd,
            angle: 0.12334, // Incorrected, just for test
        );

        $this->assertEquals(0, $milestone->unix);
        $this->assertEquals('1970-01-01 00:00:00 +00:00', $milestone->datetime);
    }
}
