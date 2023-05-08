<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\GregorianToJDNConverter;

class GregotianToJDNConverterTest extends TestCase
{
    public function testOuput(): void
    {
        // EPOCH
        $jdnC = new GregorianToJDNConverter(
            1970, 1, 1
        );

        $this->assertEquals($jdnC::EPOCH_JD, $jdnC->getMidnightJd());
        $this->assertEquals($jdnC::EPOCH_JD, $jdnC->getJd());

        $jdnC->setOffset(25200);
        $this->assertEquals(2440587.2083333, $jdnC->getMidnightJd());

        $jdnC->setOffset(-10800);
        $this->assertEquals(2440587.625, $jdnC->getMidnightJd());

        $jdnC->setDate(0, 0, 2);    # Add one day
        $jdnC->setOffset(0);        # Reset to UTC
        $this->assertEquals($jdnC::EPOCH_JD + 1, $jdnC->getJd());

        $jdnC->setDate(0, 0, 1);
        $jdnC->setTime(12);
        $this->assertEquals($jdnC::EPOCH_JD + 0.5, $jdnC->getJd());
    }
}