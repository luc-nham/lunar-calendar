<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\JdnToDateTimeConverter;
use VanTran\LunarCalendar\Converters\WinterSolsticeNewMoonConverter;

class WinterSolsticeNewMoonConverterTest extends TestCase
{
    public function testOuput(): void
    {
        // [
        //     'lunar_year' => '1901',
        //     'date' => '11-12-1901 +07:00',
        //     'jd' => 2415729.2083333335
        // ];

        $wsC = new WinterSolsticeNewMoonConverter(1901, 25200);
        $outDate = (new JdnToDateTimeConverter($wsC))->getDateTime();

        $this->assertEquals('11-12-1901 +07:00', $outDate->format('d-m-Y P'));
        $this->assertEquals(2415729.2083333, $wsC->getMidnightJd());
    }
}