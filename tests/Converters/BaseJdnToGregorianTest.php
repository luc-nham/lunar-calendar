<?php namespace VanTran\LunarCalendar\Tests\Converters;

use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Converters\BaseJdnToGregorian;
use VanTran\LunarCalendar\Interfaces\JulianDayNumberInterface;

class BaseJdnToGregorianTest extends TestCase
{
    public function testOuput(): void
    {
        $epochJd = JulianDayNumberInterface::EPOCH_JD;
        $cvt = new BaseJdnToGregorian($epochJd);

        $this->assertEquals(1970, $cvt->getYear());
        $this->assertEquals(1, $cvt->getMonth());
        $this->assertEquals(1, $cvt->getDay());
        $this->assertEquals(0, $cvt->getHour());

        $cvt = new BaseJdnToGregorian($epochJd + 0.5);
        $this->assertEquals(12, $cvt->getHour());
    }
}