<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\JdnToUtcGregorian;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

class JdnToUtcGregorianTest extends TestCase
{
    public function testOuput(): void
    {
        $epochJd = JulianDayNumberInterface::EPOCH_JD;
        $cvt = new JdnToUtcGregorian($epochJd);

        $this->assertEquals(1970, $cvt->getYear());
        $this->assertEquals(1, $cvt->getMonth());
        $this->assertEquals(1, $cvt->getDay());
        $this->assertEquals(0, $cvt->getHour());

        $cvt = new JdnToUtcGregorian($epochJd + 0.5);
        $this->assertEquals(12, $cvt->getHour());
    }
}