<?php namespace VanTran\LunarCalendar\Tests\Converters;

use Exception;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\LunarLeapMonthConverter;
use VanTran\LunarCalendar\Converters\WinterSolsticeNewMoonConverter;

class LunarLeapMonthConverterTest extends TestCase
{
    public function testHasLeap(): void
    {
        // [
        //     'lunar_year' => 2033,
        //     'leap_month' => 11,
        //     'new_moon' => '22-12-2033 00:00:00 +07:00',
        //     'timestamp' => 2018797200,
        //     'jd' => 2463953.2083333
        // ];

        $wsC = new WinterSolsticeNewMoonConverter(2033, 25200);
        $leapC = new LunarLeapMonthConverter($wsC);

        $this->assertTrue($leapC->isLeap());
        $this->assertEquals(11, $leapC->getMonth());
        $this->assertEquals(2463953.2083333, $leapC->getMidnightJd());
    }

    public function testHasNotLeap(): void
    {
        $wsC = new WinterSolsticeNewMoonConverter(2032, 25200);
        $leapC = new LunarLeapMonthConverter($wsC);

        $this->assertFalse($leapC->isLeap());
        $this->assertNull($leapC->getMonth());

        $this->expectException(Exception::class);
        $leapC->getJd();
    }
}