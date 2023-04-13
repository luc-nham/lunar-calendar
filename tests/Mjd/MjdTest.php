<?php namespace VanTran\LunarCalendar\Tests\Mjd;

use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Mjd\BaseMjd;
use VanTran\LunarCalendar\Mjd\MjdInterface;

class MjdTest extends TestCase
{
    /**
     * Kiểm tra đầu ra UTC
     * 
     * @return void 
     * @throws ExpectationFailedException 
     */
    public function testUtcOuput(): void
    {
        $epochJd = MjdInterface::EPOCH_MJD;
        $utcOffset = MjdInterface::UTC_OFFSET;

        $mjd = new BaseMjd($epochJd, $utcOffset);

        $this->assertEquals($epochJd, $mjd->getJd());
        $this->assertEquals($epochJd, $mjd->getMidnightJd());
        $this->assertEquals($utcOffset, $mjd->getOffset());
    }
}