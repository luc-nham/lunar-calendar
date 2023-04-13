<?php namespace VanTran\LunarCalendar\Tests\MoonPhases;

use DateTime;
use PHPUnit\Framework\ExpectationFailedException;
use PHPUnit\Framework\GeneratorNotSupportedException;
use PHPUnit\Framework\TestCase;
use VanTran\LunarCalendar\Mjd\DateTimeToMjd;
use VanTran\LunarCalendar\MoonPhases\MjdToNewMoonPhase;

class MjdToNewMoonPhaseTest extends TestCase
{
    /**
     * Kiểm tra khởi tạo đối tượng (Test cơ bản, không có dữ liệu đối chiếu)
     * 
     * @return void 
     * @throws ExpectationFailedException 
     * @throws GeneratorNotSupportedException 
     */
    public function testInit(): void
    {
        $mjd = new DateTimeToMjd(new DateTime());
        $newMoon = new MjdToNewMoonPhase($mjd);

        $this->assertNotEmpty($newMoon->getJd());
        $this->assertTrue($newMoon->getJd() <= $mjd->getJd());
        $this->assertEquals($mjd->getOffset(), $newMoon->getOffset());
    }
}